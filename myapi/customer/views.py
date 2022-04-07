from django.shortcuts import render
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db import connection

# Create your views here.

class Booking(APIView):

    def get(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('')
            dicts = dictfetchall(cursor)
        return Response(dicts)


class Registration(APIView):

    def post(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('customer_registration_post',[request.query_params["username"],request.query_params["password"],request.query_params["phone_no"], request.query_params["email"], request.query_params["birthdate"], request.query_params["name"]])
            dicts = dictfetchall(cursor)
        return Response(dicts)

    
def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]