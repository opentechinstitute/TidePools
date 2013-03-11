/**
 *.---.      .                    .     
 *  |  o     |                    |     
 *  |  .  .-.| .-. .,-.  .-.  .-. | .--.
 *  |  | (   |(.-' |   )(   )(   )| `--.
 *  '-' `-`-'`-`--'|`-'  `-'  `-' `-`--' v0.2
 
 *  Copyright (C) 2012-2013 Open Technology Institute <tidepools@opentechinstitute.org>
 *	Lead: Jonathan Baldwin
 *	This file is part of Tidepools <http://www.tidepools.co>

 *  Tidepools is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.

 *  Tidepools is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.

 *  You should have received a copy of the GNU General Public License
 *  along with Tidepools.  If not, see <http://www.gnu.org/licenses/>.
 */

	function getAPIs(){
		
		//BUS DATA API
		
		var currentZ = map.getZoom(); //current map zoom
	
		if (busDots !== undefined){
		
			map.removeLayer(busDots);
		}
				
		busDots = new L.LayerGroup();
		
		$.getJSON("http://bustime.mta.info/api/siri/vehicle-monitoring.json?key="+busAPIkey+"&LineRef=B61&callback=?",
	
			function(data){
	
				$.each(data.Siri.ServiceDelivery.VehicleMonitoringDelivery[0].VehicleActivity, function(x,y) { 
					
	
				  busLat = y.MonitoredVehicleJourney.VehicleLocation.Latitude;
				  busLong = y.MonitoredVehicleJourney.VehicleLocation.Longitude;
				  
	
					landmarkResize("bus.png", currentZ, function(result) {
					
						var content = '<p>Next Stop: <b>'+y.MonitoredVehicleJourney.MonitoredCall.StopPointName+'</b><br />'+y.MonitoredVehicleJourney.MonitoredCall.Extensions.Distances.PresentableDistance+'<br />Destination: '+y.MonitoredVehicleJourney.DestinationName+'</p>';	
						
									
						L.marker([busLat, busLong], {icon: result}).bindPopup(content).addTo(busDots);
	
					
					});
					
				});
				
				map.addLayer(busDots);
				
			});
		}