<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Google Map with Multiple Marker and Info Box in Laravel - CodeSolutionStuff </title>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>

<body >


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Adres Detayları</h4>
            </div>
            <div class="modal-body">

                <div class="datalar">

                    <b>Ad Soyad :<div id="adsoyad"></div>
                     Adres :<div id="adres"></div>
                     Detay :<div id="detay"></div>
                    </b>
                    <h3>Tweet  ve Whatsap Mesajı</h3>
                    <div id="detay2"></div>

                </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>






<div class="container">
    <!-- main app container -->
    <div class="readersack">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ">
                    <h3>Harita Verileri Tamamen Gönüllüler Tarafından Girilmiştir. </h3>
                    <div id="map" style='height:800px'></div>

                </div>
            </div>
        </div>
    </div>



    <!-- credits -->
    <div class="text-center">
        <p>
            <a href="#" target="_top">Api Sağlayıcı Servisi : https://api.afetharita.com/tweets/locations?format=json
            </a>
        </p>

    </div>



</div>







</body>
<script type="text/javascript">
    function initializeMap() {
        const locations = <?php echo json_encode($locations) ?>;

        const map = new google.maps.Map(document.getElementById("map"));
        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        for (var location of locations) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(location.lat, location.lng),
                map: map,
                draggable: true
            });
            bounds.extend(marker.position);
            google.maps.event.addListener(marker, 'click', (function(marker, location) {
                return function() {
                    test_modal(location.id);
                    infowindow.setContent(location.label);
                    infowindow.open(map, marker);
                }
            })(marker, location));

        }
        map.fitBounds(bounds);
    }

    function test_modal(id){

        jQuery.ajax({
            url: "{{route('datagetir')}}",
            method: 'get',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id
            },
            success: function(result){

                const myArr = JSON.parse(result);

                $("#adsoyad").html(myArr.resolution.name_surname);
                $("#adres").html(myArr.resolution.address);
                $("#detay").html(myArr.resolution.tel);
                $("#detay2").html(myArr.raw.full_text);


                $('#myModal').modal('show');



            }
        });

    }

</script>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initializeMap"></script>


</html>
