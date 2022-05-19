# Check Every Review Doc

This document introduces the implementation of project Check-Every-Review, which contains the setup of frontend, backend
and server.

## Backend

This backend service is implemented by Flask App. The ML progress is deployed in the server, and we use Flask to set up an
API, which can be reached through Nginx.

### Flask

* We use Flask to set up the Web App.
* There are two parameters in the API request, the category will be in the path of URL and the content of review is in
  request body.
* Set the `Access-Control-Allow-Credentials : true` in request header to fix the `CORS` problem.
* Return five strings in json which will be displayed in frontend.

  > * review
  > * fake possibility
  > * fake possibility description
  > * real possibility
  > * intensity

```python
app = Flask(__name__)
dp = DataProcess()
CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'


@app.route('/model/<category_name>', methods=['POST', 'GET'])
@cross_origin()
def analyze(category_name):
    review_content = request.get_json()['review']
    result = dp.process(text=review_content, category=category_name)
    possibility_fake = "{:.0%}".format(round(float(result[0]), 2))
    possibility_real = "{:.0%}".format(round(float(1 - result[0]), 2))
    description = result[1]
    intensity = get_sentiment_intensity(review_content)
    request_header = [("Access-Control-Allow-Credentials", "true")]
    return jsonify(review=review_content, possibility_fake=possibility_fake, description=description,
                   possibility_real=possibility_real, intensity=intensity), 200, request_header
```

* Set the port as 8081.

```python
if __name__ == '__main__':
    app.run(port=8081)
```

### Server

#### Method of deployment

We describe the python requirements in `requirements.txt`, 
it should be installed by pip when the service is deployed in a new environment. 

* `pip3 install -r requirements.txt`
* `python3 server.py 2>&1 &`


#### Nginx Set Up

We deploy the backend server in another server with Nginx. We set up the Nginx services to forward the HTTP port 80 to
our localhost port 8081, thus when we reach to the HTTP port of that server, we can access our Web App.

```yaml
# HTTP Server
server {
  listen  80;

  include  "/opt/bitnami/nginx/conf/bitnami/*.conf";
  location /status {
    stub_status on;
    access_log   off;
    allow all;
  }

  location /model {
    proxy_pass http://localhost:8081;
  }
}
```

## Frontend

As the whole project is implemented in `WordPress` which is mostly used for Blog set up,
We decided to implement this page by embedding HTML into `WordPress` pages, 
in which way we can design the interaction with backend with more freedom.

### HTML

#### Form Part

```html
<body>

<div id="form_div">

    <h3 class="has-neve-link-hover-color-color has-text-color">
        Spot and identify the possibility that the review is fake by&nbsp;<strong>Review Checker.</strong>
    </h3>

    <h4>You only need 3 steps:</h4>

    <ol>
        <li>Choose the&nbsp;<strong>product category of this review</strong></li>
        <li>Copy and paste&nbsp;<strong>the text of the review</strong></li>
        <li>Click the&nbsp;<strong>“CHECK”&nbsp;</strong>button</li>
    </ol>

    <form class="form_horizontal">
        <h3>The Product Category Reviewed by this Review</h3>
        <div id="selections_container">
            <label for="selections"></label>
            <select id="selections" class="form_item">
                <option value="Kitchens">Kitchens</option>
                <option value="Electronics">Electronics</option>
                <option value="Sports">Sports</option>
                <option value="Cloths">Cloths</option>
                <option value="Movies">Movies</option>
            </select>
        </div>

        <h3>Copy and Paste the Text of Suspected Reviews</h3>
        <div id="description_input_div">
            <label for="description_input"></label>
            <label>
                <textarea name="Text1" cols="160" rows="5" id="description_input" class="form_item"></textarea>
            </label>
        </div>

        <div style="max-width:60%">
            <button type="button" id="submit_button" onmousedown="requestData()">
                CHECK
            </button>
        </div>
    </form>
</div>
```

#### Analysis Details Part

```html

<p style="font-style:italic; font-size:20px; font-weight:bold; margin-top:20px">Analysis Details</p>


<div class="analysis_block">
    <div class="analysis_tag_div">
        <p class="analysis_tag">Review Content</p>
        <p class="analysis_tag_description">This feature shows the text of the review that you are checking.</p>
    </div>
    <div class="analysis_result_div">
        <p id="review_display" class="analysis_result"></p>
    </div>
</div>

<div class="block"><span style="white-space:pre" class="line">   </span></div>

<div class="analysis_block">
    <div class="analysis_tag_div">
        <p class="analysis_tag">Possibility of Fake</p>
        <p class="analysis_tag_description">This feature shows the probability that the review is a fake one.</p>
    </div>
    <div class="analysis_result_div">
        <p id="possibility_fake_display" class="analysis_result"></p>
        <p id="possibility_fake_description"></p>
    </div>
</div>

<div class="block"><span style="white-space:pre" class="line">   </span></div>

<div class="analysis_block">
    <div class="analysis_tag_div">
        <p class="analysis_tag">Possibility of Real</p>
        <p class="analysis_tag_description">This feature shows the probability that the review is a real one.</p>
    </div>
    <div class="analysis_result_div">
        <p id="possibility_real_display" class="analysis_result"></p>
    </div>
</div>

<div class="block"><span style="white-space:pre" class="line">   </span></div>

<div class="analysis_block">
    <div class="analysis_tag_div">
        <p class="analysis_tag">Emotional Intensity</p>
        <p class="analysis_tag_description">This feature shows the emotional tendency of the review, including positive,
            neutral, and negative sentiment.</p>
    </div>
    <div class="analysis_result_div">
        <p id="intensity_display" class="analysis_result"></p>
    </div>
</div>

<div class="block"><span style="white-space:pre" class="line">   </span></div>

<div class="block"><span style="white-space:pre" class="line">   </span></div>

</body>
```

###JavaScript

We use origin js to design the action of the button, 
the request we send and how the response is going to be displayed.

```javascript
function requestData() {
        const category = selections.value;
        const descriptionValue = description_input.value;
        const URL = "http://54.206.116.159/model/" + category;
        const request = new XMLHttpRequest();
        request.open("POST", URL, true);
        request.setRequestHeader("Content-Type", "application/json");
        const sendValue = {review: descriptionValue};
        request.send(JSON.stringify(sendValue));

        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.getResponseHeader('content-type') === 'application/json') {
                    const result = JSON.parse(request.responseText);
                    document.getElementById("review_display").innerHTML = result.review;
                    document.getElementById("possibility_fake_display").innerHTML = result.possibility_fake;
                    document.getElementById("possibility_fake_description").innerHTML = result.description;
                    document.getElementById("possibility_real_display").innerHTML = result.possibility_real;
                    document.getElementById("intensity_display").innerHTML = result.intensity;
                } else {
                    console.log(request.responseText);
                }
            }
        }
    }
```
