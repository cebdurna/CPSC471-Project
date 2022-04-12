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
            cursor.callproc('')
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


class Registration(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerlogin')
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

class Login(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerloginget', [request.query_params["username"], request.query_params["password"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

class Booking(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerbookingget', [request.query_params["customerID"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

class Invoice_Detail(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerinvoicedetailcharges', [request.query_params["invoiceID"]])

            charges = dictfetchall(cursor)

            cursor.close()

            cursor = connection.cursor()

            cursor.callproc('customerinvoicedetailpayments', [request.query_params["invoiceID"]])

            payments = dictfetchall(cursor)
            finaldict = {"Charges": charges, "Payments":payments}
            return Response(finaldict)
        return Response()

class Invoice(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('customerinvoiceget', [request.query_params["customerID"]])

            dicts = dictfetchall(cursor)

            return Response(dicts)
        return Response()

    def post(self, request):
        #if request.query_params["jwt"]
        with connection.cursor() as cursor:
            cursor.callproc('customer_registration_post',[request.query_params["username"],request.query_params["password"],request.query_params["phone_no"], request.query_params["email"], request.query_params["birthdate"], request.query_params["name"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]
