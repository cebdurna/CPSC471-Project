from django.urls import path
from rest_framework import status

from . import views

urlpatterns = [
    path('booking', views.Booking.as_view(), name='booking'),
    path('login', views.Login.as_view(), name='login'),
    path('invoice_detail', views.Invoice_Detail.as_view(), name='invoice_detail'),
    path('invoice', views.Invoice.as_view(), name='invoice')
]