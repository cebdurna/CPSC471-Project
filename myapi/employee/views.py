from django.shortcuts import render
from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from django.db import connection


# Create your views here.

class Invoice(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('invoice_getall')
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('invoice_post', [request.query_params["invoice_id"], request.query_params["form"], request.query_params["date_created"], request.query_params["date_due"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

class Invoice_Detail(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('invoice_detail_get', [request.query_params["invoice_id"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

    def put(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('invoice_detail_update', [request.query_params["invoice_id"],request.query_params["form"], request.query_params["date_created"],request.query_params["date_due"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


class Charge(APIView):
    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employee_invoice_detail_charge', [request.query_params["invoice_id"], request.query_params["description"], request.query_params["tax"], request.query_params["price"],request.query_params["charge_time"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


class Payment(APIView):
    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employee_invoice_detail_payment', [request.query_params["invoice_id"], request.query_params["cc_no"], request.query_params["amount"], request.query_params["date"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


class Service(APIView):
    def delete(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('services_delete', [request.query_params["service_id"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

    def put(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('services_update', [request.query_params["service_id"], request.query_params["hotel_id"], request.query_params["description"],request.query_params["price"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('services_post', [request.query_params["service_id"], request.query_params["hotel_id"], request.query_params["description"],request.query_params["price"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('services_get')
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

class Booking(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('bookings_get')
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
    
    def delete(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('bookings_delete',[request.query_params["book_no"],request.query_params["customer_id"], request.query_params["hotel_id"], request.query_params["room_no"] ] )
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]