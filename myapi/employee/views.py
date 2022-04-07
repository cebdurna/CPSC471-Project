from django.shortcuts import render
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db import connection


# Create your views here.

class Invoice_Detail(APIView):

    def get(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('')
            dicts = dictfetchall(cursor)
        return Response(dicts)


class Charge(APIView):

    def post(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('')
            dicts = dictfetchall(cursor)
        return Response(dicts)


class Payment(APIView):
    def post(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('employee_invoice_detail_payment', [request.query_params["invoice_id"], request.query_params["cc_no"], request.query_params["amount"], request.query_params["date"]])
            dicts = dictfetchall(cursor)
        return Response(dicts)

def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]