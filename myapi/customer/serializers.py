from rest_framework import serializers

class BookingSerializer(serializers.Serializer):
    bookingNumber = serializers.CharField()
    roomNumber = serializers.CharField()
    check_in_date = serializers.DateField()
    check_out_date = serializers.DateField()
    cc_number = serializers.CharField()
    invoiceID = serializers.CharField()
    capacity = serializers.IntegerField()
    total = serializers.FloatField()
