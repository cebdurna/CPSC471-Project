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
        
class Bookings(APIView):
    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeebookingsget', [request.query_params["hotelID"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()
        
    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeebookingspost', [request.query_params["customerID"], request.query_params["roomNumber"], request.query_params["checkInDate"], request.query_params["checkOutDate"], request.query_params["ccNumber"], request.query_params["ccName"], request.query_params["ccExpiry"], request.query_params["cvv"], request.query_params["ccAddress"], request.query_params["ccPostal"]])
            dicts = dictfetchall(cursor)
            return Response()
        return Response()
        
    def put(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('employeebookingsput', [request.query_params["bookingNo"], request.query_params["roomNumber"], request.query_params["checkInDate"], request.query_params["checkOutDate"], request.query_params["ccNumber"], request.query_params["ccName"], request.query_params["ccExpiry"], request.query_params["cvv"], request.query_params["ccAddress"], request.query_params["ccPostal"]])
            dicts = dictfetchall(cursor)
            return Response()
        return Response()
      
    def delete(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('bookings_delete',[request.query_params["book_no"],request.query_params["customer_id"], request.query_params["hotel_id"], request.query_params["room_no"] ] )
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()


class Invoice_Detail(APIView):
    def post(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('invoice_post', [request.query_params["invoice_id"], request.query_params["form"], request.query_params["date_created"], request.query_params["date_due"]])
            dicts = dictfetchall(cursor)
            return Response(dicts)
        return Response()

    def get(self, request):
        with connection.cursor() as cursor:
            cursor.callproc('invoice_detail_get_charges', [request.query_params["invoice_id"]])

            charges = dictfetchall(cursor)

            cursor.close()

            cursor = connection.cursor()

            cursor.callproc('invoice_detail_get_payment', [request.query_params["invoice_id"]])

            payments = dictfetchall(cursor)
            finaldict = {"Charges": charges, "Payments":payments}
            return Response(finaldict)
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

def dictfetchall(cursor):
    desc = cursor.description
    return [
        dict(zip([col[0] for col in desc], row))
        for row in cursor.fetchall()
    ]