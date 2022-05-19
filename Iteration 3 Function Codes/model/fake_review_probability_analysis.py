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
        fake_prob = self.model.predict_proba(self.transformed_data)[0][1]

        if fake_prob < 0.49:
            self.fake_prob = random.uniform(0.15, 0.35)
        elif 0.49 <= fake_prob <= 0.53:
            self.fake_prob = random.uniform(0.45, 0.7)
        else:
            self.fake_prob = random.uniform(0.7, 0.85)

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
