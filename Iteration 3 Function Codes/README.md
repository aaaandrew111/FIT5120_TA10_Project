# Check Every Review Doc

This document introduces the implementation of project Check-Every-Review, which contains the setup of frontend, backend
and server.

## Backend

This backend service is implemented by Flask App. The ML progress is deployed in the server, and we use Flask to set up an
API, which can be reached through Nginx.

### Interact with Machine Learning Models

* The machine learning models are trained and saved.
* The function takes users' input and outputs the possibility of review being fake, description and sentiment analysis of review

Fake Review probability prediction
```python
# import required packages
import pickle
import random
import re

import nltk
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer, PorterStemmer
from nltk.tokenize import RegexpTokenizer


# build a class object
class DataProcess:

    # init the class
    def __init__(self):
        self.fake_prob = None
        self.description = None
        self.category = None
        self.text = None
        self.categories = ['Kitchens', 'Electronics', 'Sports', 'Cloths', 'Movies']
        nltk.download('stopwords')
        nltk.download('wordnet')
        nltk.download('omw-1.4')
        return

    # Load model -> transform data -> make prediction -> return the prediction
    def process(self, text, category):
        self.text = text
        self.category = category
        self.load_model()
        self.transform_data()
        self.make_prediction()
        return [self.fake_prob, self.description]

    # loading the corresponding models and transformers
    # For example:
    #   load model and tfidf-vectorizer of <Kitchens> if user chooses <Kitchens> in the drop-down list in the website.
    def load_model(self):
        path = './models/' + self.category + ".pkl"
        with open(path, 'rb') as file:
            model = pickle.load(file)
        file.close()

        path = './transformers/' + self.category + ".pickle"
        with open(path, 'rb') as file:
            transformer = pickle.load(file)
        file.close()

        self.model = model
        self.transformer = transformer
        return

    # cleaning text data and using tfidf-vectorizer to transform text data
    def transform_data(self):
        sentence = str(self.text)

        # lower case
        sentence = sentence.lower()

        # remove special characters
        sentence = sentence.replace('{html}', "")
        cleaner = re.compile('<.*?>')
        cleantext = re.sub(cleaner, '', sentence)
        rem_url = re.sub(r'http\S+', '', cleantext)
        rem_num = re.sub('[0-9]+', '', rem_url)

        # tokenization
        tokenizer = RegexpTokenizer(r'\w+')
        tokens = tokenizer.tokenize(rem_num)

        # remove stopwords
        filtered_words = [w for w in tokens if len(w) > 2 if not w in stopwords.words('english')]
        # filtered_words = [w for w in tokens if len(w) > 2]

        lemmatizer = WordNetLemmatizer()
        stemmer = PorterStemmer()
        # stemming and lemmatization
        stem_words = [stemmer.stem(w) for w in filtered_words]
        # stem_words = filtered_words
        lemma_words = [lemmatizer.lemmatize(w) for w in stem_words]

        cleansed_sentence = " ".join(lemma_words)
        self.transformed_data = self.transformer.transform([cleansed_sentence])
        return

    # make fake review probability prediction
    def make_prediction(self):
        self.fake_prob = self.model.predict_proba(self.transformed_data)[0][1]
        
        if self.fake_prob <= 0.35:
            self.description = 'The possibility is low. It is most likely not a fake review.'
        elif 0.45 <= self.fake_prob <= 0.7:
            self.description = 'The possibility is a little high. Pay attention to this review.'
        else:
            self.description = 'The possibility is very high. This review is almost certain to be fake.'

        # print(self.model.classes_)
        return


if __name__ == '__main__':
    # demo for user input
    user_input_text = 'cute and reasonably price. I am happy with my purchase.'
    user_input_category = 'Kitchens'

    # initialize the class and get the result
    d = DataProcess()
    res = d.process(user_input_text, user_input_category)

    # first element is fake probability
    fake_probability = "{:.0%}".format(round(float(res[0]), 2))
    # second element is fake description
    fake_description = res[1]
    # real probability = 1 - fake probability
    real_probability = "{:.0%}".format(1 - round(float(res[0]), 2))

    # just for showcasing output
    print(res)
    print(fake_probability)
    print(fake_description)
    print(real_probability)
```

Sentiment Analysis
```python
from textblob import TextBlob


# helper function for assigning sentiment intensity tag to user text
def calculate_polarity(pol):
    pol_scale = ''

    if pol >= 0.3:
        if pol <= 0.5:
            pol_scale = 'Weak Positive'
        else:
            pol_scale = 'Strong Positive'

    elif 0.3 > pol > -0.3:
        pol_scale = 'Neutral'

    else:
        if pol >= -0.5:
            pol_scale = 'Weak Negative'
        else:
            pol_scale = 'Strong Negative'

    return pol_scale


# function for getting polarity score of user text
def get_sentiment_intensity(user_text):
    tb = TextBlob(user_text)

    pol = tb.sentiment.polarity
    # sub = tb.sentiment.subjectivity

    pol_scale = calculate_polarity(pol)

    return pol_scale


# main function
if __name__ == '__main__':
    user_text = 'This is really bad product. I hate it.'

    # get the string result 
    res = get_sentiment_intensity(user_text)

    # just for showcasing the sample output
    print(res)
```


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

### JavaScript

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
