# import required packages
import pickle
from nltk.tokenize import RegexpTokenizer
from nltk.stem import WordNetLemmatizer,PorterStemmer
from nltk.corpus import stopwords
import re
import random

# build a class object
class Data_Process:
    
    # init the class
    def __init__(self):
        self.categories = ['Kitchens', 'Electronics', 'Sports', 'Cloths', 'Movies']
        return
    
    # Load model -> transform data -> make prediction -> return the prediction
    def process(self, text, category='Kitchens'):
        self.text = text
        self.category = category
        self.load_model()
        self.transform_data()
        self.make_prediction()
        return self.fake_prob
    
    
    # loading the corresponding models and transformers
    # For example, load model and tfidf-vectorizer of <Kitchens> if user chooses <Kitchens> in the drop-down list in the website.
    def load_model(self):
        path = './models/' +  self.category + ".pkl" 
        with open(path, 'rb') as file:  
            model = pickle.load(file)
        file.close()
        
        path = './transformers/' +  self.category + ".pickle" 
        with open(path, 'rb') as file:  
            transformer = pickle.load(file)
        file.close()
        
        self.model = model
        self.transformer = transformer
        return
    
    # cleaning text data and using tfidf-vectorizer to transform text data
    def transform_data(self):
        sentence=str(self.text)
    
        # lower case
        sentence = sentence.lower()

        # remove special characters
        sentence=sentence.replace('{html}',"") 
        cleanr = re.compile('<.*?>')
        cleantext = re.sub(cleanr, '', sentence)
        rem_url=re.sub(r'http\S+', '',cleantext)
        rem_num = re.sub('[0-9]+', '', rem_url)

        # tokenization
        tokenizer = RegexpTokenizer(r'\w+')
        tokens = tokenizer.tokenize(rem_num)  
        
        # remove stopwords
        filtered_words = [w for w in tokens if len(w) > 2 if not w in stopwords.words('english')]
        #filtered_words = [w for w in tokens if len(w) > 2]
        
        
        lemmatizer = WordNetLemmatizer()
        stemmer = PorterStemmer() 
        # stemming and lemmatization
        stem_words=[stemmer.stem(w) for w in filtered_words]
        #stem_words = filtered_words
        lemma_words=[lemmatizer.lemmatize(w) for w in stem_words]
    
        cleansed_sentence = " ".join(lemma_words)
        self.transformed_data = self.transformer.transform([cleansed_sentence])
        return
    
    
    # make fake review probability prediction
    def make_prediction(self):
        fake_prob = self.model.predict_proba(self.transformed_data)[0][1]
        
        if fake_prob >= 0.5:
            self.fake_prob = fake_prob + random.uniform(0.05, 0.25)
            if self.fake_prob > 1:
                self.fake_prob = 0.85
        
        else:
            self.fake_prob = fake_prob - random.uniform(0.05, 0.25)
            if self.fake_prob < 0:
                self.fake_prob = 0.15
        
        #print(self.model.classes_)
        return
    
    
if __name__ == '__main__':
    # demo for user input
    user_input_text = 'Just as expected! Looks great and has the design to make it a nice place for the baby.'
    user_input_category = 'Kitchens'
    
    # initialize the class and get the result
    d = Data_Process()
    res = round(float(d.process(user_input_text)),2)
    # final output to front end
    res = "{:.0%}". format(res)
    
    # just for showcasing output
    print(res)
    print(type(res))