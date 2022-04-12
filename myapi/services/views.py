from django.shortcuts import render
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db import connection
from rest_framework.exceptions import APIException

# Create your views here.

class Services(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('servicesget', [request.query_params["hotel"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
        
        
        
def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]