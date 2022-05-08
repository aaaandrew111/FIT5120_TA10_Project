from textblob import TextBlob

# helper function for assigning sentiment intensity tag to user text
def calculate_polarity(pol):
    
    pol_scale = ''
    
    if pol >= 0.3:
        if pol <= 0.5:
            pol_scale = 'Weak Positive'
        else:
            pol_scale = 'Strong Positive'
    
    elif pol < 0.3 and pol > -0.3:
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
    #sub = tb.sentiment.subjectivity
    
    pol_scale = calculate_polarity(pol)
    
    return pol_scale


# main function
if __name__ == '__main__':
    
    user_text = 'This is really bad product. I hate it.'
    
    # get the string result 
    res = get_sentiment_intensity(user_text)
    
    # just for showcasing the sample output
    print(res)