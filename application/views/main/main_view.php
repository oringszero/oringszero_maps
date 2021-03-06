<!DOCTYPE html> 
<html> 
<head> 
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"> 
    <title>2016년 서울특별시 사망 교통사고 내역</title> 
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.1.min.js"></script>
    <script type="text/javascript" src="https://openapi.map.naver.com/openapi/v3/maps.js?clientId=<?php echo $naver_client_id;?>&submodules=geocoder"></script> 
</head> 
<body> 
<div id="map" style="width:100%;height:800px;"></div>

    <script>

        var map = new naver.maps.Map('map', {
            center: new naver.maps.LatLng(37.5666805, 126.9784147),
            zoom: 6
        });

        var markers = [],
            infoWindows = [];

        $.ajax({
            type        : 'get',
            cache       : true,
            url         : '/main/getData',
            dataType    : 'json',
            success	: function(data) {
                var obj = data.searchResult.accidentDeath;

                var _this, grd_la, grd_lo, datetime;
                $.each(obj, function(key, value) {
                    
                    _this = value;
                    grd_la = _this.grd_la;
                    grd_lo = _this.grd_lo;
                    datetime = _this.dt_006+_this.dt_006_lv8;
                    datetime = datetime.substring(0,4)+"-"+datetime.substring(4,6)+"-"+datetime.substring(6,8)+" "+datetime.substring(8,10)+":"+datetime.substring(10,12)+":00";

                    var position = new naver.maps.LatLng(grd_la, grd_lo);

                    naver.maps.Service.reverseGeocode({
                        location: position,
                    }, function(status, response) {
                        if (status !== naver.maps.Service.Status.OK) {
                            return alert('Something wrong!');
                        }

                        var result = response.result, // 검색 결과의 컨테이너
                        item = result.items; // 검색 결과의 배열

                        var marker = new naver.maps.Marker({
                            map: map,
                            position: position,
                            zIndex: 100
                        });

                        var infoWindow = new naver.maps.InfoWindow({
                            content: `<div style="width:420px;text-align:center;padding:10px;"><b>사고 발생 날짜 : ${datetime}</br> 사고 장소 : ${item[0].address} </b></div>`
                        });

                        markers[key] = marker;
                        infoWindows[key] = infoWindow;

                        naver.maps.Event.addListener(markers[key], 'click', getClickHandler(key));
                    })

                });
            }
        });
        
        naver.maps.Event.addListener(map, 'idle', function() {
            updateMarkers(map, markers);
        });

        function updateMarkers(map, markers) {

            var mapBounds = map.getBounds();
            var marker, position;

            for (var i = 0; i < markers.length; i++) {

                marker = markers[i]
                position = marker.getPosition();

                if (mapBounds.hasLatLng(position)) {
                    showMarker(map, marker);
                } else {
                    hideMarker(map, marker);
                }
            }
        }

        function showMarker(map, marker) {

            if (marker.setMap()) return;
            marker.setMap(map);
        }

        function hideMarker(map, marker) {

            if (!marker.setMap()) return;
            marker.setMap(null);
        }

        // 해당 마커의 인덱스를 seq라는 클로저 변수로 저장하는 이벤트 핸들러를 반환합니다.
        function getClickHandler(seq) {
            return function(e) {
                var marker = markers[seq],
                    infoWindow = infoWindows[seq];

                if (infoWindow.getMap()) {
                    infoWindow.close();
                } else {
                    infoWindow.open(map, marker);
                }
            }
        }

    </script> 
</body> 
</html>