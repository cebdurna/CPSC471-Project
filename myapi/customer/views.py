from django.shortcuts import render
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db import connection
from rest_framework.exceptions import APIException

# Create your views here.

class Booking(APIView):

    def get(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('customerlogin')
            dicts = dictfetchall(cursor)
        return Response(dicts)

class Login(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerloginget', [request.query_params["username"], request.query_params["password"]])
            dicts = dictfetchall(cursor)
            return Response(next(iter(dicts)))
        return Response()
        
class Booking(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerbookingget', [request.query_params["customerID"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


    
def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]