from django.urls import path
from rest_framework import status

from . import views

urlpatterns = [
    path('', views.Services.as_view(), name='services'),
]