from flask import Flask, request, jsonify
from flask_cors import CORS, cross_origin

from fake_review_probability_analysis import DataProcess
from sentiment_intensity_analysis import get_sentiment_intensity

app = Flask(__name__)
dp = DataProcess()
CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'


@app.route('/model/<category_name>', methods=['POST', 'GET'])
@cross_origin()
def analyze(category_name):
    review_content = request.get_json()['review']
    print("Category: " + category_name + " Review-Content: " + review_content)
    result = dp.process(text=review_content, category=category_name)
    possibility_fake = "{:.0%}".format(round(float(result[0]), 2))
    possibility_real = "{:.0%}".format(round(float(1 - result[0]), 2))
    description = result[1]
    intensity = get_sentiment_intensity(review_content)
    request_header = [("Access-Control-Allow-Credentials", "true")]
    return jsonify(review=review_content, possibility_fake=possibility_fake, description=description,
                   possibility_real=possibility_real, intensity=intensity), 200, request_header


if __name__ == '__main__':
    app.run(debug=True, port=8081)
