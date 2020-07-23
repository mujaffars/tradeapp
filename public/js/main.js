$(function(){
    $('.btnAddPerson').click(function(){ 
        var modalSkeleton = genModalSkeleton("Item Memo"); 
        $(modalSkeleton).modal("show");
        
        $.ajax({ 
            url: '/ajax/add-person',
            type: 'POST',
            dataType: 'html',
            async: true,
            error: function(){},
            success: function(resp){
                $(modalSkeleton).find('#ModalShellBody').html(resp);
                
                $(modalSkeleton).find('.btnSavePerson').click(function(){
                    var fdata = {
                        'firstName': $(modalSkeleton).find('#firstName').val(),
                        'lastName': $(modalSkeleton).find('#lastName').val(),
                        'gender': $(modalSkeleton).find('#gender').val(),
                        'dob':$(modalSkeleton).find('#dob').val(),
                        'occupation':$(modalSkeleton).find('#occupation').val(),
                        'nativePlace':$(modalSkeleton).find('#nativePlace').val(),
                        'petName':$(modalSkeleton).find('#petName').val(),
                        'profile_img':$(modalSkeleton).find('#profileImg').val(),
                        'actionCase':'SAVE'
                    }
                    
                    $.ajax({ 
                        url: '/ajax/add-person',
                        type: 'POST',
                        data:fdata,
                        dataType: 'json',
                        async: true,
                        error: function(){},
                        success: function(resp){
                            if(resp.status == 'saved'){
                                $(modalSkeleton).find(".clsPAddNotice").show();
                                $(modalSkeleton).find(".clsPAddNotice").slideUp('slow');
                                $(modalSkeleton).find(".clsPAddNotice").slideDown('slow');
                            }
                        }
                    });
                    
                })
            }
        });
    })
    
    /* Code to make relationship */
    $('.btnMakeRelationship').click(function(){ 
        var modalSkeleton = genModalSkeleton("Make relationship"); 
        $(modalSkeleton).modal("show");        
		
        $.ajax({ 
            url: '/ajax/make-relationship',
            type: 'POST',
            dataType: 'html',
            async: true,
            error: function(){},
            success: function(resp){
                $(modalSkeleton).find('#ModalShellBody').html(resp);
                
                $(modalSkeleton).find('.btnMakeRelationship').click(function(){
                    var fdata = {
                        'person_id_1': $(modalSkeleton).find('#person_id_1').val(),
                        'relation_type': $(modalSkeleton).find('#relation_type').val(),
                        'person_id_2': $(modalSkeleton).find('#person_id_2').val(),
                        'actionCase':'SAVE'
                    }
                    
                    $.ajax({ 
                        url: '/ajax/make-relationship',
                        type: 'POST',
                        data: fdata,
                        dataType: 'json',
                        async: true,
                        error: function(){},
                        success: function(resp){
                            if(resp.status == 'saved'){
                                $(modalSkeleton).find(".clsRAddNotice").show();
                                $(modalSkeleton).find(".clsRAddNotice").slideUp('slow');
                                $(modalSkeleton).find(".clsRAddNotice").slideDown('slow');
                            }
                        }
                    });
                    
                })
            }
        });
		
    })
	
    /* Code to check relationship */
    $('.btnCheckRelationship').click(function(){ 
        var modalSkeleton = genModalSkeleton("Check relationship"); 
        $(modalSkeleton).modal("show");        
		
        $.ajax({ 
            url: '/ajax/check-relationship',
            type: 'POST',
            dataType: 'html',
            async: true,
            error: function(){},
            success: function(resp){
                $(modalSkeleton).find('#ModalShellBody').html(resp);
			
                $(modalSkeleton).find('.clsRemoveRelation').click(function(){      
                    var fdata = {
                        'actionCase': 'Delete',
                        'relation_id': $(this).attr('relationId')
                    }	
                    $.ajax({ 
                        url: '/ajax/check-relationship',
                        type: 'POST',
                        data: fdata,
                        dataType: 'json',
                        async: true,
                        error: function(){},
                        success: function(resp){
                            if(resp.status == 'deleted'){
                                $(modalSkeleton).find("[relationId="+resp.relation_id+"]").parent().parent().remove();
                            }
                        }
                    });		
                })
    
            }
        });
		
    })   
    
    $('.window').mouseup(function(){ 
        var personId = $(this).attr('personId');
        var offset = $(this).offset();
        console.log(offset.left+" "+$('.demo').scrollLeft());
        
        var fdata = {
            'personId': personId,
            'left': eval(offset.left - 20 + $('.demo').scrollLeft()),
            'top': eval(offset.top - 95 + $('.demo').scrollTop())
        }
                    
        $.ajax({ 
            url: '/ajax/modify-position',
            type: 'POST',
            data:fdata,
            dataType: 'json',
            async: true,
            error: function(){},
            success: function(resp){
                
            }
        });
                    
    });
    
    $(".window").dblclick("click", function () {
        var modalSkeleton = genModalSkeleton("Check relationship"); 
        $(modalSkeleton).modal("show");   
        
        var fdata = {
            'personId': $(this).attr('personid')
        }
                    
        $.ajax({ 
            url: '/ajax/edit-person',
            type: 'POST',
            data:fdata,
            dataType: 'html',
            async: true,
            error: function(){},
            success: function(resp){
                $(modalSkeleton).find('#ModalShellBody').html(resp);
			
                $(modalSkeleton).find('.btnSavePerson').click(function(){      
                    var fdata = {
                        'personId': $(modalSkeleton).find('#personId').val(),
                        'firstName': $(modalSkeleton).find('#firstName').val(),
                        'lastName': $(modalSkeleton).find('#lastName').val(),
                        'gender': $(modalSkeleton).find('#gender').val(),
                        'dob':$(modalSkeleton).find('#dob').val(),
                        'occupation':$(modalSkeleton).find('#occupation').val(),
                        'nativePlace':$(modalSkeleton).find('#nativePlace').val(),
                        'petName':$(modalSkeleton).find('#petName').val(),
                        'profileImg':$(modalSkeleton).find('#profileImg').val(),
                        'actionCase':'Modify'
                    }	
                    $.ajax({ 
                        url: '/ajax/edit-person',
                        type: 'POST',
                        data: fdata,
                        dataType: 'json',
                        async: true,
                        error: function(){},
                        success: function(resp){
                            if(resp.status == 'modified'){
                                $(modalSkeleton).modal('hide');
                            }
                        }
                    });		
                })
            }
        });
    });
    
});

jsPlumb.ready(function() {			

    var color = "gray";

    var instance = jsPlumb.getInstance({
        // notice the 'curviness' argument to this Bezier curve.  the curves on this page are far smoother
        // than the curves on the first demo, which use the default curviness value.			
        Connector : [ "Straight", {
            curviness:14
        } ],
        DragOptions : {
            cursor: "pointer", 
            zIndex:2000
        },
        PaintStyle : {
            strokeStyle:color, 
            lineWidth:1
        },
        EndpointStyle : {
            radius:9, 
            fillStyle:color
        },
        HoverPaintStyle : {
            strokeStyle:"#ec9f2e"
        },
        EndpointHoverStyle : {
            fillStyle:"#ec9f2e"
        },
        Container:"chart-demo"
    });
		
    // suspend drawing and initialise.
    instance.doWhileSuspended(function() {		
        // declare some common values:
        var arrowCommon = {
            foldback:0.1, 
            fillStyle:color, 
            width:10
        },
        // use three-arg spec to create two different arrows with the common values:
        overlays = [
        [ "Arrow", {
            location:0.9
        }, arrowCommon ]
        ];

        // add endpoints, giving them a UUID.
        // you DO NOT NEED to use this method. You can use your library's selector method.
        // the jsPlumb demos use it so that the code can be shared between all three libraries.
        var windows = jsPlumb.getSelector(".chart-demo .window");
        for (var i = 0; i < windows.length; i++) {
            instance.addEndpoint(windows[i], {
                uuid:windows[i].getAttribute("id") + "-left",
                anchor:"Left",
                maxConnections:-1
            });
            instance.addEndpoint(windows[i], {
                uuid:windows[i].getAttribute("id") + "-right",
                anchor:"Right",
                maxConnections:-1
            });
            instance.addEndpoint(windows[i], {
                uuid:windows[i].getAttribute("id") + "-bottom",
                anchor:"Bottom",
                maxConnections:-1
            });
            instance.addEndpoint(windows[i], {
                uuid:windows[i].getAttribute("id") + "-top",
                anchor:"Top",
                maxConnections:-1
            });
        }
	
        var pos = 'bottom';
        //instance.connect({uuids:["chartWindow3-bottom", "chartWindow6-top" ], overlays:overlays, detachable:true, reattach:true});
        var jsonString = $('#txtJsonString').val();
                
        var objRelationship = jQuery.parseJSON(jsonString);
                
        $.each(objRelationship, function(index, val) { 
            $.each(val, function(index2, val2) {
                if(val2.type == 'fatherOf'){
                    instance.connect({
                        uuids:["chartWindow"+val2.selfId+"-bottom", "chartWindow"+val2.personWith+"-top" ], 
                        overlays:overlays
                    });
                }
                if(val2.type == 'husbandOf'){
                    instance.connect({
                        uuids:["chartWindow"+val2.selfId+"-right", "chartWindow"+val2.personWith+"-left" ], 
                        overlays:overlays
                    });
                }
            })
        })      
                
        instance.draggable(windows);		
    });

    jsPlumb.fire("jsPlumbDemoLoaded", instance);
});