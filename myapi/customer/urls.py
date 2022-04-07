from django.urls import path
from rest_framework import status

from . import views

urlpatterns = [
    path('booking', views.Booking.as_view(), name='booking'),
    path('registration',views.Registration.as_view(), name='registration'),
]