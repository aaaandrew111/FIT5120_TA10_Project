# 需要用到的库
import pickle
from nltk.tokenize import RegexpTokenizer
from nltk.stem import WordNetLemmatizer,PorterStemmer
from nltk.corpus import stopwords
import re

# 建立一个class类
class Data_Process:
    
    # 初始化class
    def __init__(self):
        self.categories = ['Kitchens', 'Electronics', 'Sports', 'Cloths', 'Movies']
        return
    
    # 加载模型，处理数据，进行预测流程，最终返回预测结果
    def process(self, text, category):
        self.text = text
        self.category = category
        self.load_model()
        self.transform_data()
        self.make_prediction()
        return self.fake_prob
    
    
    # 加载对应模型和transformer
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
    
    # 对数据进行清洗转换
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
    
    # 进行fake review 可能性预测
    def make_prediction(self):
        self.fake_prob = self.model.predict_proba(self.transformed_data)[0][1]
        #print(self.model.classes_)
        return
    
    
if __name__ == '__main__':
    # 模拟用户输入
    user_input_text = 'Just as expected! Looks great and has the design to make it a nice place for the baby to'
    user_input_category = 'Kitchens'
    
    # 初始化类并运行类函数<process>
    d = Data_Process()
    res = float(d.process(user_input_text, user_input_category))
    res = round(res, 2)
    
    # 打印结果（仅仅用于开发人员看返回结果是否符合预期）
    print(res)
    print(type(res))