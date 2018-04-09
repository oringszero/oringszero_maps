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
<?php
    echo '<ul id="traffic_grd" style="display:none;">';
    foreach ($traffic_reault['searchResult']['accidentDeath'] as $key => $val) {

        $datetime = new DateTime($val['dt_006'].$val['dt_006_lv8'].'00');
        $datetime = $datetime->format('Y-m-d H:i:s');

        echo '<li data-grd-la="'.$val['grd_la'].'" data-grd-lo="'.$val['grd_lo'].'" data-datetime="'.$datetime.'"></li>';
    }
    echo '</ul>';
?>


    <script>
        var map = new naver.maps.Map('map', {
            center: new naver.maps.LatLng(37.5666805, 126.9784147),
            zoom: 6
        });

        var markers = [],
            infoWindows = [];
        $('#traffic_grd > li').each(function () {
            
            var _this = $(this);
            var position = new naver.maps.LatLng( _this.data('grd-la'), _this.data('grd-lo'));

            var marker = new naver.maps.Marker({
                map: map,
                position: position,
                zIndex: 100
            });

            naver.maps.Service.reverseGeocode({
                location: new naver.maps.LatLng( _this.data('grd-la'), _this.data('grd-lo')),
            }, function(status, response) {
                    if (status !== naver.maps.Service.Status.OK) {
                        return alert('Something wrong!');
                    }

                    var result = response.result, // 검색 결과의 컨테이너
                    item = result.items; // 검색 결과의 배열
                })
            
            var infoWindow = new naver.maps.InfoWindow({
                content: '<div style="width:320px;text-align:center;padding:10px;"><b>사고 발생 날짜 : '+ _this.data('datetime') +'</b></div>'
            });

            markers.push(marker);
            infoWindows.push(infoWindow);
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

        for (var i=0, ii=markers.length; i<ii; i++) {
            naver.maps.Event.addListener(markers[i], 'click', getClickHandler(i));
        }
    </script> 
</body> 
</html>