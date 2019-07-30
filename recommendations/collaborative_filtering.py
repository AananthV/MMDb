import pandas as pd
import numpy as np

import db_helper

from sklearn.metrics.pairwise import pairwise_distances

movie_ids, user_ids, movie_data, movie_ratings, ratings = db_helper.get_ratings();

user_similarity = pairwise_distances(ratings, metric='cosine')

mean_user_rating = ratings.mean(axis=1)
ratings_diff = (ratings - mean_user_rating[:, np.newaxis])
user_prediction = mean_user_rating[:, np.newaxis] + user_similarity.dot(ratings_diff) / np.array([np.abs(user_similarity).sum(axis=1)]).T

item_similarity = pairwise_distances(ratings.T, metric='cosine') + pairwise_distances(movie_data, metric='cosine');

item_prediction = ratings.dot(item_similarity) / np.array([np.abs(item_similarity).sum(axis=1)])

for user in range(len(user_prediction) -1):
    for i in range(len(user_prediction[user])):
        if(ratings[user][i] != 0):
            user_prediction[user][i] = -1
    top_ten = np.argpartition(user_prediction[user], -10)[-10:]
    recommended = []
    for movie in top_ten[np.argsort(-top_ten)]:
        recommended.append(movie_ids[movie])
    db_helper.set_user_recommendations(user_ids[user], 0, recommended)

for user in range(len(item_prediction) - 1):
    for i in range(len(item_prediction[user])):
        if(ratings[user][i] != 0):
            item_prediction[user][i] = -1
    top_ten = np.argpartition(item_prediction[user], -10)[-10:]
    recommended = []
    for movie in top_ten[np.argsort(-top_ten)]:
        recommended.append(movie_ids[movie])
    db_helper.set_user_recommendations(user_ids[user], 1, recommended)

for item in range(len(item_similarity)):
    top_ten = np.argpartition(item_similarity[item], -25)[-25:]
    recommended = []
    for movie in top_ten[np.argsort(movie_ratings[movie_ids[-top_ten]])]:
        recommended.append(movie_ids[movie])
    db_helper.set_movie_recommendations(movie_ids[item], recommended[:10])
