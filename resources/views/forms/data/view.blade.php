<div class="modal fade modalDataView" id="modalDataView" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<script type="">
function launchModalView(id) {
	console.log("launchModalView(id) id - ",id);
	$("#modalDataView .modal-body").empty();
	$.ajax({
		type    :"GET",
		dataType:"json",
		url     :"{!! url('/forms/data/"+ id + "')!!}",
		success :function(data) {
			console.log("data - ", data);
			if (data[0] !== null) {
				$("#modalDataView .modal-title").text(data[0].name);
				$("#modalDataView .modal-header i").remove();
				$("#modalDataView .modal-header").append("<i>"+data[0].purpose+"</i>");
			}
			
			if (data[1] !== null) {
				for (var i = 0; i < data[1].length; i++) {
					var opts = JSON.parse(data[1][i].options);
					console.log("    opts - ", opts);
					var wrapper = document.createElement('div');
					wrapper.style.clear = "both";
					wrapper.style.padding = "10px 0";
					var wLabel = document.createElement('div');
					wLabel.className = "col-md-3";
					var wVal = document.createElement('div');
					wVal.className = "col-md-9";
					wVal.style.whiteSpace = "pre";
					var label = data[1][i].label;
					$(wLabel).append('<b>'+label+'</b>');
					var val = "";
					if (data[2][data[1][i].name]) val = data[2][data[1][i].name];
					if (data[1][i].type == "boolean") {
						if (val == 0) val = opts[false];
						else if (val == 1) val = opts[true];
					}
					if (Array.prototype.isPrototypeOf(val)) {
						for (var vi = 0; vi < val.length; vi++) {
							$(wVal).append(val[vi]);
							if (vi < val.length - 1) $(wVal).append(', ');
						}
					}
					else $(wVal).append(val);
					$(wVal).append('&nbsp;');
					$(wrapper).append(wLabel);
					$(wrapper).append(wVal);
					$("#modalDataView .modal-body").append(wrapper);
				}
			}
		}
	});
}
</script>