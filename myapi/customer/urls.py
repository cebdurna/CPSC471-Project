from django.urls import path
from rest_framework import status

from . import views

urlpatterns = [
    path('booking', views.Booking.as_view(), name='booking'),
    path('login', views.Login.as_view(), name='login'),
]