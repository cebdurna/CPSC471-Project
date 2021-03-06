from django.urls import path
from rest_framework import status

from . import views

urlpatterns = [
    path('invoice_detail/charge', views.Charge.as_view(), name='charge'),
    path('invoice_detail/payment', views.Payment.as_view(), name='payment'),
    path('invoice_detail', views.Invoice_Detail.as_view(), name='invoice_detail'),
    path('invoice', views.Invoice.as_view(), name='invoice'),
    path('services', views.Service.as_view(), name='services'),
    path('login', views.Login.as_view(), name='login'),
    path('rooms', views.Rooms.as_view(), name='rooms'),
    path('bookings', views.Bookings.as_view(), name='bookings'),
]