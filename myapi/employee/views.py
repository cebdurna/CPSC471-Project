from django.shortcuts import render
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db import connection


# Create your views here.

class Login(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeeloginget', [request.query_params["username"], request.query_params["password"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
        
class Rooms(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeeroomsget', [request.query_params["hotel"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
        
    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeeroomspost', [request.query_params["room_no"], request.query_params["hotelID"], request.query_params["type"], request.query_params["beds"], request.query_params["floor"], request.query_params["furniture"], request.query_params["capacity"], request.query_params["orientation"], request.query_params["rate"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
        
    def put(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeeroomsput', [request.query_params["room_no"], request.query_params["hotelID"], request.query_params["type"], request.query_params["beds"], request.query_params["floor"], request.query_params["furniture"], request.query_params["capacity"], request.query_params["orientation"], request.query_params["rate"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
        
    def delete(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeeroomsdelete', [request.query_params["hotelID"], request.query_params["roomNo"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

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
            cursor.callproc('employee_invoice_detail_charge', [request.query_params["invoice_id"], request.query_params["description"], request.query_params["tax"], request.query_params["price"],request.query_params["charge_time"]])
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