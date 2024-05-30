// import * as THREE from './three';
/*
 * Camera Buttons
 */
// var THREE = require('./three');
var image_data;
var line_name = []
var glob_matadata = {}
var glob_temp
var old_cor = [];
var B3D;
var checkforCallback = 0
var newWallCorner = [];
var textureImg = '';
var selText = ''
var APP_UOM = 'IN';
var price = {
    items: [],
    others: [],
    discount: 0,
    total: 0,
    promo_type: '',
    promo_val: ''
}
var backEdge;

//var api_url='http://localhost/designer-global/';
var api_url='';

var oldPos = new THREE.Vector3();
var disc_inp = '';
// var disc_filter=[ {  id: 1, name: '%', type: 'per',val:'5'  }]
var jsonstackindex = 0;
var jsonstack = []
var cust_status = 'new';
var sum_type = "summary-viewer";
var promo_percent = 10;
var default_wall_height = 244;
var discount_arr = [

    { id: 1, name: '%', type: 'per', val: '0' },
    { id: 2, name: '$', type: 'amnt', val: '0' }
];

var set_dr_flag = { "Wall Cabinets": {}, "Base Cabinets": {}, "Tall Cabinets": {},"Sink Cabinets":{},"Add Ons":{} }
var CameraButtons = function (blueprint3d) {
    var orbitControls = blueprint3d.three.controls;
    var three = blueprint3d.three;

    var panSpeed = 30;
    var directions = {
        UP: 1,
        DOWN: 2,
        LEFT: 3,
        RIGHT: 4
    }

    function init() {
        // Camera controls
        $("#zoom-in").click(zoomIn);
        $("#zoom-out").click(zoomOut);
        $("#zoom-in").dblclick(preventDefault);
        $("#zoom-out").dblclick(preventDefault);

        $("#reset-view").click(three.centerCamera)

        $('#straight-view').click(() => {
            three.topCamera('straight')
        });
        $('#top-view').click(() => {
            three.topCamera('top')
        });

        $("#move-left").click(function () {
            pan(directions.LEFT)
        })
        $("#move-right").click(function () {
            pan(directions.RIGHT)
        })
        $("#move-up").click(function () {
            pan(directions.UP)
        })
        $("#move-down").click(function () {
            pan(directions.DOWN)
        })

        $("#move-left").dblclick(preventDefault);
        $("#move-right").dblclick(preventDefault);
        $("#move-up").dblclick(preventDefault);
        $("#move-down").dblclick(preventDefault);
    }

    function preventDefault(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function pan(direction) {
        switch (direction) {
            case directions.UP:
                orbitControls.panXY(0, panSpeed);
                break;
            case directions.DOWN:
                orbitControls.panXY(0, -panSpeed);
                break;
            case directions.LEFT:
                orbitControls.panXY(panSpeed, 0);
                break;
            case directions.RIGHT:
                orbitControls.panXY(-panSpeed, 0);
                break;
        }
    }

    function zoomIn(e) {
        e.preventDefault();
        orbitControls.dollyIn(1.1);
        orbitControls.update();
    }

    function zoomOut(e) {
        e.preventDefault;
        orbitControls.dollyOut(1.1);
        orbitControls.update();
    }

    init();
}
function itemSelected(item) {
    console.log('itemSele', item)
    selectedItem = item;
    let selectedwall = JSON.parse($('#change-wall').val())
    if (!selectedItem.hasOwnProperty('textureUrl')) {
        selectedItem['textureUrl'] = 'images/models/textures/transparent.png';
        selectedItem['textureName'] = 'None';
        selectedItem['prev_textureUrl'] = 'images/models/textures/transparent.png';
        selectedItem['prev_textureName'] = 'None';
    }

    selectedItem['prev_textureUrl'] = selectedItem.textureUrl;
    selectedItem['prev_textureName'] = selectedItem.textureName;

    if (!selectedItem.hasOwnProperty('wallId'))
        selectedItem['wallId'] = selectedwall.id
    $("#context-menu-name").text("Item - " + item.metadata.itemName);
    // window.getColors(item.metadata.itemType)
    APP_UOM = $('#uom_container select').val();
    window.setMeasurementValue(APP_UOM);

    if (item.metadata.group == "Base Cabinets" || item.metadata.group == "Wall Cabinets" || item.metadata.group == "Tall Cabinets" || item.metadata.group == "Sink Cabinets" || item.metadata.group == "Add Ons")
        $('#edit-sel-config').show()
    else
        $('#edit-sel-config').hide()

	if (item.metadata.group == "Appliances")
		$('#context-menu-copy').hide();
	else
		$('#context-menu-copy').show();

    $("#add-standard-items").hide();
    $("#add-items").hide();
    $("#context-menu").show();

    $("#fixed").prop('checked', item.fixed);
    $("#show_measure").prop('checked', item.show_measure);
    if (item.metadata.itemType != 8 && item.metadata.itemType != 3) {
        $("#colorOptions").show();
    } else
        $("#colorOptions").hide();
        window.getMeasureInfo(B3D).then(mesh=>{
            buildAvailSpaceVar()
        })
//     mesh.forEach(mes => {
//         if (mes.id == selectedItem.wallId) {
// //            console.log('meswall', mes)
//             setWallname = JSON.stringify(mes)
//             $('#change-wall').val(setWallname)
//             window.buildWallInfoHTML(setWallname)
//             wallID = mes.id
//             setWallname = JSON.parse(JSON.stringify(mes))
//             B3D.three.setWallName(setWallname);

//         }
//     })


}

function buildBoxforCopy() {
    return new Promise(async resolve => {
        let pos = selectedItem.position;
        let size = selectedItem.halfSize;
        let minR;
        let minL;
        let maxR;
        let maxL;
        let boxL;
        let boxR;
        let boxArry = [];
        let selectedwall = JSON.parse($('#change-wall').val())
        let wallCorr_val = 0;
        let incType = 'z'
        selectedwall.interiorStart[incType] = selectedwall.interiorStart.y;
        selectedwall.interiorEnd[incType] = selectedwall.interiorEnd.y;
        if (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) + 10 > selectedwall.total_space && (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) - 10 < selectedwall.total_space))
            incType = 'x';
        if (Math.abs(Math.round(selectedwall.interiorStart.x)) - Math.round(selectedwall.interiorEnd.x) != 0 && Math.abs(selectedwall.interiorStart.y) - Math.abs(selectedwall.interiorEnd.y) != 0)
            wallCorr_val = Math.abs(Math.abs(selectedwall.interiorStart.y) - Math.abs(selectedwall.interiorEnd.y)) / selectedwall.total_space
        // else if(Math.abs(selectedwall.interiorStart.y) - Math.abs(selectedwall.interiorEnd.y)!=0 && Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x)!=0)
        // wallCorr_val = Math.abs(Math.abs(selectedwall.interiorStart.y) - Math.abs(selectedwall.interiorEnd.y))/selectedwall.total_space
        // console.log('wallCorr_val', wallCorr_val)
        selectedwall.interiorEnd['z'] = selectedwall.interiorEnd.y;
        selectedwall.interiorStart['z'] = selectedwall.interiorStart.y;





        if (incType == 'x') {
            minR = new THREE.Vector3((pos.x + size.x + 0.05) + (size.x * wallCorr_val), pos.y - size.y, pos.z - size.z - (size.z * wallCorr_val))
            maxR = new THREE.Vector3((minR.x + 2 * size.x - 0.05) + (size.x * wallCorr_val), pos.y + size.y, pos.z + size.z - (size.z * wallCorr_val))
            boxR = new THREE.Box3(minR, maxR)
            boxArry.push(boxR)
            maxL = new THREE.Vector3((pos.x - size.x - 0.05) - (size.x * wallCorr_val), pos.y + size.y, pos.z + size.z + (size.z * wallCorr_val))
            minL = new THREE.Vector3((maxL.x - 2 * size.x + 0.05) - (size.x * wallCorr_val), pos.y - size.y, pos.z - size.z + (size.z * wallCorr_val))
            boxL = new THREE.Box3(minL, maxL)
            boxArry.push(boxL)
            if ((selectedwall.interiorEnd.x - selectedwall.interiorStart.x) < 0) {
                boxArry[0] = boxArry.splice(1, 1, boxArry[0])[0];
            }
        }
        if (incType == 'z') {
            minR = new THREE.Vector3((pos.x - size.z) - (size.z * wallCorr_val), pos.y - size.y, pos.z + size.x + 0.05 + (size.x * wallCorr_val))
            maxR = new THREE.Vector3((pos.x + size.z) - (size.z * wallCorr_val), pos.y + size.y, minR.z + 2 * size.x - 0.05 - (size.x * wallCorr_val))
            boxR = new THREE.Box3(minR, maxR)
            boxArry.push(boxR)
            maxL = new THREE.Vector3((pos.x + size.z) + (size.z * wallCorr_val), pos.y + size.y, pos.z - size.x - 0.05 - (size.x * wallCorr_val))
            minL = new THREE.Vector3((pos.x - size.z) + (size.z * wallCorr_val), pos.y - size.y, maxL.z - 2 * size.x + 0.05 + (size.x * wallCorr_val))
            boxL = new THREE.Box3(minL, maxL)
            boxArry.push(boxL)
            if ((selectedwall.interiorEnd.y - selectedwall.interiorStart.y) < 0) {
                boxArry[0] = boxArry.splice(1, 1, boxArry[0])[0];
            }

            console.log('boxArry', boxArry)
        }
        B3D.model.checkforvalidPos(boxArry[0]).then(data => {

            if ((selectedwall.interiorEnd[incType] - selectedwall.interiorStart[incType]) > 0) {
                if (data.isInter == false && Math.floor(data.boundBox.max[incType]) <= Math.round(selectedwall.interiorEnd[incType]) && Math.floor(data.boundBox.max[incType]) >= Math.round(selectedwall.interiorStart[incType]))
                    resolve(data)
                else {
                    B3D.model.checkforvalidPos(boxArry[1]).then(data1 => {
                        console.log('data*11*', data1)
                        if (data1.isInter == false && Math.floor(data1.boundBox.min[incType]) <= Math.round(selectedwall.interiorEnd[incType]) && Math.floor(data1.boundBox.min[incType]) >= Math.round(selectedwall.interiorStart[incType]))
                            resolve(data1)
                        else
                            resolve({ isInter: true })
                    })
                }
            }
            else {
                console.log(data.boundBox.min[incType], selectedwall.interiorEnd[incType], selectedwall.interiorEnd[incType])
                if (data.isInter == false && Math.ceil(data.boundBox.min[incType]) >= Math.round(selectedwall.interiorEnd[incType]) && Math.ceil(data.boundBox.min[incType]) <= Math.round(selectedwall.interiorStart[incType]))
                    resolve(data)
                else {
                    B3D.model.checkforvalidPos(boxArry[1]).then(data1 => {
                        console.log('gree11', data1, Math.round(selectedwall.interiorStart[incType]), Math.floor(data1.boundBox.max[incType]), Math.round(selectedwall.interiorEnd[incType]))
                        if (data1.isInter == false && Math.floor(data1.boundBox.max[incType]) >= Math.round(selectedwall.interiorEnd[incType]) && Math.floor(data1.boundBox.max[incType]) <= Math.round(selectedwall.interiorStart[incType]))
                            resolve(data1)
                        else
                            resolve({ isInter: true })
                    })
                }
            }
        })
    })
}

function itemUnselected() {
    selectedItem = null;
    $("#context-menu").hide();
    $("#colorOptions").hide();
    $("#colorOptions li").removeClass("active");
    $('#edit-sel-config').hide()
}
/*
 * Context menu for selected item
 */
var selectedItem;
var wallID = '';
var ContextMenu = function (blueprint3d) {

    var scope = this;

    var three = blueprint3d.three;

    function init() {
        $("#context-menu-delete").click(function (event) {
            // selectedItem.remove();
            $('#fetching-container').show();
            $("#cart_count").text(Number($("#cart_count").text()) - 1);
          
            detectchanges(selectedItem, 'del');
            saveDesigns('auto-save');
            //				if(jsonstackindex > 0)
            jsonstackindex++;
            setButtonState()
            // console.log('selectedItem.remove()',selectedItem.remove())
              setTimeout(function () {
            selectedItem.remove();
            window.getMeasureInfo().then(_=>{
                buildAvailSpaceVar()
            })

            }, 50)
        });
        $("#context-menu-copy").click(function (event) {
            // alert()
            $('#fetching-container').show();
            setTimeout(_=>{
                $("#context-menu-copy").prop('disabled', true);
                if (["Appliances"].indexOf(selectedItem.metadata.group) == -1) {
                    let metadata = {};
                    Object.keys(selectedItem.metadata).forEach(data => {
                        if (data == 'def_sizes')
                            metadata[data] = { width: 2 * (selectedItem.halfSize.x), height: 2 * (selectedItem.halfSize.y), depth: 2 * (selectedItem.halfSize.z) }
                        else
                            metadata[data] = selectedItem.metadata[data]
                    })
    
                    window.buildBoxforCopy().then(res => {
                        if (res.isInter == false) {
                            let textureInfo = { textureUrl: selectedItem.textureUrl, textureName: selectedItem.textureName }
                            blueprint3d.model.scene.addItem(metadata.itemType, metadata.modelUrl, metadata, textureInfo, res.pos).then(_=>{
                                detectchanges(selectedItem, 'copy');
                                saveDesigns('auto-save');
                                jsonstackindex = 0;
                                $("#cart_count").text(Number($("#cart_count").text()) + 1);
                                $('.undo').prop('disabled', false);
                                $("#context-menu-copy").prop('disabled', false);
                            })
                        } else {
                            alert('There is no space in left or right of the copied item.')
                            $("#context-menu-copy").prop('disabled', false);
                        }
                        $('#fetching-container').hide();
                    })
                }
                else
                    {alert('Appliances cannot be copied. Instead of copy try by adding the item directly.');
                    $("#context-menu-copy").prop('disabled', false);}
            },50)
      
        });
        three.itemSelectedCallbacks.add(itemSelected);
        three.itemUnselectedCallbacks.add(itemUnselected);

        initResize();

        $("#fixed").click(function () {
            var checked = $(this).prop('checked');
            selectedItem.setFixed(checked);
        });
        $("#show_measure").click(function () {
            console.log($(this))
            var checked = $(this).prop('checked');
            selectedItem.setMeasureView(checked);
        });

    }







    function initResize() {
        $("#item-height-ft").change(window.resize);
        $("#item-height-in").change(window.resize)
        $("#item-width-ft").change(window.resize);
        $("#item-width-in").change(window.resize);
        $("#item-depth-ft").change(window.resize);
        $("#item-depth-in").change(window.resize)
        $("#item-height").change(window.resize);
        $("#item-width").change(window.resize);
        $("#item-depth").change(window.resize);

    }

    // function itemUnselected() {
    //     selectedItem = null;
    //     $("#context-menu").hide();
    //     $("#colorOptions").hide();
    //     $("#colorOptions li").removeClass("active");
    // }

    init();
}

/*
 * Loading modal for items
 */

var ModalEffects = function (blueprint3d) {

    var scope = this;
    var blueprint3d = blueprint3d;
    var itemsLoading = 0;

    this.setActiveItem = function (active) {
        itemSelected = active;
        update();
    }

    function update() {
        if (itemsLoading > 0) {
            $("#loading-container").show();
        } else {
            $("#loading-container").hide();
        }
    }

    $("#items_tab").click(function () {
        itemsLoading = 0;
        update();
    });

    $("#standard_items_tab").click(function () {
        itemsLoading = 0;
        update();
    });

    $('#my-design').click(function () {
        let imageList = $('#view-mydesign')

        $("#my-design span").removeClass("active");
        $("#my-design span.opened").addClass("active");

        let designhtml = ''
        window.getData("getDesign", '').then(data => {
            console.log('wi', data)
            designhtml += '<div><ul>'
            if (data && data.length) {
                let draftItem = {
                    id: "draft", image_path: DraftDesignName, json_path: DraftDesignName, status: "", type: "", userid: "1"
                }

                data.unshift(draftItem)
                data.forEach(item => {
                    if (item.id == 'draft')
                        designhtml += '<li id="draft"><img src="saved_designs/auto-save/images/' + item.image_path + '.png" onclick=loadMyDesign(' + item.image_path + ',"saved_designs/auto-save/data/")><span class="name">Draft Design</span></li>'
                    else
                        designhtml += '<li><span id="' + item.id + '" class="glyphicon glyphicon-trash delete"></span><img src="saved_designs/my-design/images/' + item.image_path + '.png" onclick=loadMyDesign(' + item.image_path + ',"saved_designs/my-design/data/")><span class="name">MyDesign - ' + item.id + '</span></li>'
                })
            }
            designhtml += '</ul></div>'
            imageList.html(designhtml);
            initDeleteDesign();
            /*
                        $('#view-mydesign span.delete').on("click", function (evt) {
                            let ele = this;
                            initDeleteDesign(evt, ele);
                        })
            */

        });

    })



    $("#model_preload_tab").click(function () {
        $('#my-design').trigger('click');
        $("#wall-details").hide();
        $("#uom_container").hide();

        $("#preload-viewer").height(window.innerHeight - 40);

        itemsLoading = 0;
        update();
    });

    function init() {
        blueprint3d.model.scene.itemLoadingCallbacks.add(function () {
            itemsLoading += 1;
            update();
        });

        blueprint3d.model.scene.itemLoadedCallbacks.add(function () {
            itemsLoading -= 1;
            update();
        });

        update();
    }

    init();
}

/*
 * Side menu
 */

var SideMenu = function (blueprint3d, floorplanControls, modalEffects) {
    var blueprint3d = blueprint3d;
    var floorplanControls = floorplanControls;
    var modalEffects = modalEffects;

    var ACTIVE_CLASS = "active";

    var tabs = {
        "FLOORPLAN": $("#floorplan_tab"),
        "STDSHOP": $("#standard_items_tab"),
        "SHOP": $("#items_tab"),
        "DESIGN": $("#design_tab"),
        "MODELPRELOAD": $("#model_preload_tab"),
        "SUMMARY": $("#view_summary")
    }

    var scope = this;
    this.stateChangeCallbacks = $.Callbacks();

    this.states = {
        "DEFAULT": {
            "div": $("#viewer"),
            "tab": tabs.DESIGN

            //            "div": $("#floorplanner"),
            //            "tab": tabs.FLOORPLAN
        },
        "FLOORPLAN": {
            "div": $("#floorplanner"),
            "tab": tabs.FLOORPLAN
        },
        /*
                "STDSHOP": {
                    "div": $("#add-standard-items"),
                    "tab": tabs.STDSHOP
                },
                "SHOP": {
                    "div": $("#add-items"),
                    "tab": tabs.SHOP
                },
        */
        "MODELPRELOAD": {
            "div": $("#preload-viewer"),
            "tab": tabs.MODELPRELOAD
        },
        "SUMMARY": {
            "div": $("#summary-viewer"),
            "tab": tabs.SUMMARY
        }
    }

    // sidebar state
    var currentState = scope.states.FLOORPLAN;

    function init() {
        for (var tab in tabs) {
            var elem = tabs[tab];
            elem.click(tabClicked(elem));
        }

        $("#update-floorplan").click(floorplanUpdate);

        initLeftMenu();

        blueprint3d.three.updateWindowSize();
        handleWindowResize();

        initItems();

        setCurrentState(scope.states.DEFAULT);

    }

    function floorplanUpdate() {
        closeAllPopups();
        $("#uom_container").css("margin", "15px 30% 0 50%");
        $("#sidebar").css('width', '25%');
        $(".nav-sidebar").show();
        $(".nav-sidebar").next("hr").show();
        $("#wall-details").show();
        $('#wall-ht select').val(default_wall_height);
        newWallCorners = blueprint3d.model.floorplan.getWalls();
        var currentWallCor = validateWallMovement(newWallCorners);

        updateWallItem(currentWallCor);
        window.getMeasureInfo(blueprint3d).then(_=>{
            buildAvailSpaceVar()
        })
        setCurrentState(scope.states.DEFAULT);

    }
    function updateWallItem(cur_cor) {
        old_cor.forEach((val, key) => {
            cur_cor.forEach((val1, key1) => {
                //                console.log("val1", val1, key1)

                if (key == key1) {
                    //                    console.log("key",key, "key1: ",key1)

                    if ((val.start.x != val1.start.x) && (val1.id == val.id) && (val.end.x != val1.end.x)) {
                        //                        console.log('corners', val, val1)
                        let difference = val.start.x + val1.start.x;

                        // console.log('difference1',difference)

                        var wallItems = getWallItems(val1.id);

                        if (wallItems.length) {
                            wallItems.forEach(res => {
                                // if (difference<0 || difference==0 )
                                //     res.position.set(val1.start.x+res.halfSize.z,res.position.y,res.position.z)
                                // else if(difference>0 || difference!=0 )
                                //  res.position.set(val1.start.x-res.halfSize.z,res.position.y,res.position.z)
                                // })
                                if (difference != 0) {
                                    if (val1.start.x > res.position.z)
                                        res.position.set(val1.start.x - res.halfSize.z, res.position.y, res.position.z)
                                    else
                                        res.position.set(val1.start.x + res.halfSize.z, res.position.y, res.position.z)


                                }

                            })
                        }
                    }
                    if ((val.start.y != val1.start.y) && (val1.id == val.id) && (val.end.y != val1.end.y)) {
                        let difference = val.start.y + val1.start.y;

                        // console.log('difference2',difference)

                        var wallItems = getWallItems(val1.id);

                        if (wallItems.length) {
                            wallItems.forEach(res => {
                                if (difference < 0 || difference == 0)
                                    res.position.set(res.position.x, res.position.y, val1.start.y + res.halfSize.z)
                                else if (difference > 0 || difference != 0)
                                    res.position.set(res.position.x, res.position.y, val1.start.y, -res.halfSize.z)
                            })
                        }
                    }
                }
            })
        })

    }
    function getWallItems(id) {
        let wallItem = []
        newWallCorners.forEach(res => {
            if (res.id == id) {
                // console.log('items',items)
                if (res.items)
                    res.items.forEach(load => { wallItem.push(load) })
                if (res.onItems)
                    res.onItems.forEach(load => { wallItem.push(load) })


            }
        })
        // console.log('updatedWall',wallItem)
        return wallItem;
    }
    function validateWallMovement(corners) {

        let wallCorner = [];
        corners.forEach(res_cor => {
            let start = res_cor.getStart();
            let end = res_cor.getEnd();
            let cornerData = {
                id: res_cor.id,
                start: { x: start.x, y: start.y },
                end: { x: end.x, y: end.y }
            }
            wallCorner.push(cornerData);
        })
        // console.log('wallCorner',wallCorner)
        return wallCorner;

    }

    function tabClicked(tab) {
        return function () {
            // Stop three from spinning
            blueprint3d.three.stopSpin();

            if (tab[0].id == "design_tab") {
                $("#add-items").hide();
                $("#add-standard-items").hide();
            }

            // Selected a new tab
            for (var key in scope.states) {
                var state = scope.states[key];
                if (state.tab == tab) {
                    setCurrentState(state);
                    break;
                }
            }
        }
    }

    function setCurrentState(newState) {
        $('#viewer-control').hide()

        if (currentState == newState) {
            $('#viewer-control').show()
            return;
        }

        // show the right tab as active
        if (currentState.tab !== newState.tab) {
            if (currentState.tab != null) {
                currentState.tab.removeClass(ACTIVE_CLASS);
            }
            if (newState.tab != null) {
                newState.tab.addClass(ACTIVE_CLASS);
            }
        }

        // set item unselected
        blueprint3d.three.getController().setSelectedObject(null);

        // show and hide the right divs
        currentState.div.hide()
        newState.div.show()

        // custom actions
        if (newState == scope.states.FLOORPLAN) {
            floorplanControls.updateFloorplanView();
            floorplanControls.handleWindowResize();

        }

        if (currentState == scope.states.FLOORPLAN) {
            blueprint3d.model.floorplan.update();
        }

        if (newState == scope.states.DEFAULT) {
            blueprint3d.three.updateWindowSize();
            $('#viewer-control').show()
            // window.getMeasureInfo(B3D).then(_=>{
            //     buildAvailSpaceVar()
            // })
        }

        if (newState == scope.states.SUMMARY) {
            $('#fetching-container').show();
            $(".nav-sidebar").hide();
            $(".nav-sidebar").next("hr").hide();
            $("#wall-details").hide();
            $("#sidebar").css('width', '12.5%');
            $("#prev_summary").hide();
            $("#prev_tab").show();
            sum_type = 'summary-viewer';
            $("#uom_container").removeClass('uom-left').addClass('uom-center');
            setTimeout(_ => {
                window.prepareSummmaryHTML(blueprint3d, APP_UOM).then(grouped_items => {

                    console.log('getPrice', grouped_items)
                    var html = grpdQty(grouped_items);
                    if (sum_type == 'summary-viewer')
                        loadPriceSummary();

                    $('#' + sum_type + ' #content').html(html);
                    $('.more-rev').click(function () { $(this).prev().show();$(this).next().show(); })
                   $('.hide-rev').click(function () { $(this).prev().prev().hide();$(this).hide(); })
                    $('#fetching-container').hide();

                });
            }, 100)

        }

        // set new state
        handleWindowResize();
        currentState = newState;

        scope.stateChangeCallbacks.fire(newState);
        var oldWallCorners = blueprint3d.model.floorplan.getWalls();
        old_cor = validateWallMovement(oldWallCorners)
    }

    function initLeftMenu() {
        $(window).resize(handleWindowResize);
        handleWindowResize();
    }

    function handleWindowResize() {
        //        $("#sidebar").height(window.innerHeight);
        //        $("#add-items").height(window.innerHeight);
        $("#add-items").height("auto");
    };

    // TODO: this doesn't really belong here
    function initItems() {
        $("#add-items, #add-standard-items").find(".add-item").mousedown(function (e) {
            var modelUrl = $(this).attr("model-url");
            var temp = $(this)
            var itemType = parseInt($(this).attr("model-type"));
            var metadata = {
                itemName: $(this).attr("model-name"),
                resizable: true,
                modelUrl: modelUrl,
                itemType: itemType,
                imageUrl: $(this).attr("model-img"),
                group: $(this).attr("model-grp"),
                sku: $(this).attr("model-sku"),
                uom: $(this).attr("model-uom"),
                price: $(this).attr("model-price"),
                is_default: false,
                material_cost: $(this).attr("model-materialcost"),
                labor_cost: $(this).attr("model-laborcost"),
                measurements: JSON.parse(atob($(this).attr("model-measurements"))),
                def_sizes: JSON.parse(atob($(this).attr("model-def_sizes"))),
                door_material: '',
                door_style: '',
                drawer_style: '',
                door_color_name: '',
                door_color_url: ''
            }

            if ($(this).attr("model-status") == 1) {
                if (metadata.group == 'Base Cabinets' || metadata.group == 'Tall Cabinets' || metadata.group == 'Wall Cabinets' || metadata.group == 'Sink Cabinets' || metadata.group == 'Add Ons') {
                    $('#config-others').show()
                    if(metadata.group=='Add Ons')
                        $('#config-others').hide();
                

                    if (Object.keys(set_dr_flag[metadata.group]).length == 0) {
                        glob_matadata = metadata
                        glob_temp = temp;
                        $('#saveConfig').show()
                        $('#set-all-config').hide();
                        // console.log($('#fetching-container'))

                        if (selected_doorConfig.material.mat_name)
                            selected_doorConfig.material = {};
                        let idx = { fin: 0, col: 0 }
                        if ((selected_doorConfig.handle_fin).hasOwnProperty('idx') && selected_doorConfig.handle_fin.idx + 1) {
                            idx.fin = selected_doorConfig.handle_fin.idx + 1;
                            idx.col = selected_doorConfig.handle_col.idx + 1;
                        }

                        createDropdown().then(_ => {
//                            console.log('alert********')

                            let group_label_arr = metadata.group.split(" ");
                            let group_label = (group_label_arr[0] == "Base" ? "Base" : "Wall");
                            if(metadata.group=='Add Ons')
                                group_label = 'Add Ons'
                            $("#door-config-modal #group_title").text(group_label + " Style Configuration");
                        })
                    } else {
                        set_dr_flag[metadata.group]
                        metadata['door_config'] = set_dr_flag[metadata.group]
                        window.addItems(metadata, temp);
                        setCurrentState(scope.states.DEFAULT);
                    }
                }
                else {
                    window.addItems(metadata, temp);
                    setCurrentState(scope.states.DEFAULT);
                }
            }
        });
    }

    init();

}
function checkforBound(box) {
    let res = true;
    let selectedwall = JSON.parse($('#change-wall').val())
    selectedwall.interiorEnd['z'] = selectedwall.interiorEnd.y;
    let incType = 'z'
    if (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) + 10 > selectedwall.total_space && (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) - 10 < selectedwall.total_space))
        incType = 'x'
    if (incType == 'z') {
        if (Math.ceil(box.min[incType]) < Math.round(selectedwall.interiorEnd[incType]) && (selectedwall.interiorEnd.y - selectedwall.interiorStart.y) < 0)
            res = false
        if ((Math.floor(box.max[incType]) > Math.round(selectedwall.interiorEnd[incType]) && selectedwall.interiorEnd.y - selectedwall.interiorStart.y) > 0) {
            res = false
        }
    }
//    console.log('incType', incType)
    if (incType == 'x') {

        if (Math.floor(box.max[incType]) > Math.round(selectedwall.interiorEnd[incType]) && (selectedwall.interiorEnd.x - selectedwall.interiorStart.x) > 0)
            res = false
        if (Math.ceil(box.min[incType]) < Math.round(selectedwall.interiorEnd[incType]) && (selectedwall.interiorEnd.x - selectedwall.interiorStart.x) < 0)
            res = false
    }
    return res;

}

var doorConfig = {
    material: [],
    door_style: [],
    drawer_front: [],
    door_color: []
}
var selected_doorConfig = { colors: {}, doors: { idx: 0 }, material: {}, drawer: {}, handle_fin: {}, handle_col: {}, corbels: {}, moldings: {}, valances: {}, floating_shelf: {}, misc_item: {}, hinges: {}, drawer_slides: {} }
function createDDforHandFinish(selectedIdx) {
    window.getData('getHandleFinish', '').then(handle => {
//        console.log('handle', handle)
        buildJson(handle, 'item_name', 'pulls/').then(han => {
            let def_idx = 0
            if (selectedIdx)
                def_idx = selectedIdx - 1;
            createSlector('#hn-finish', han, 'handle_fin', def_idx);
        })
    })
}


function createDDforHandleColor(id, selectedIdx) {
    return new Promise(resolve => {
        let model = $("#door-config-modal");
//        console.log('pullid', id, selectedIdx)
        window.getData('getHandleColors', id).then(handleC => {
            if (handleC) {
                buildJson(handleC, 'pull_name', 'pulls/').then(handle_c => {
                    let def_idx = 0
                    if (selectedIdx)
                        def_idx = selectedIdx - 1;
                    createSlector('#hn-color', handle_c, 'handle_col', def_idx);
                    resolve()

                })
            }
            else {
                resolve()
            }
        })
    })

}
function createDropdown() {

    return new Promise(resolve => {
        $('#fetching-container').show();
        setTimeout(_ => {
            if (selected_doorConfig.material.hasOwnProperty('idx')) {
                createDropdownDoor(selected_doorConfig.material.mat_type, selected_doorConfig.doors.idx + 1)

            }
            else {
                window.getData('getMaterial', '').then(material => {
//                    console.log('mate', material)
                    buildJson(material, 'mat_name', 'doors/').then(mat => {
                        createSlector('#dr-material', mat, 'material');
                    })
                    resolve()
                })

            }
        }, 100)
    })

}
function createDropdownDoor(id, selectedIdx) {
    // $('#fetching-container').show();
    window.getData('getDoorStyle', id).then(style => {
        if (style) {
            let def_idx = 0
            if (selectedIdx)
                def_idx = selectedIdx - 1;
            buildJson(style, 'prod_name', 'doors/', def_idx).then(style_j => {
                createSlector('#dr-style', style_j, 'doors', def_idx);
            })
        }
    })
}
function createDrawerDropDown(arr, selectedId) {

    return new Promise(resolve => {
        if (arr) {
            buildJson(arr, 'name', 'doors/').then(drawer_j => {
                let def_idx = 0
                if (selectedId)
                    def_idx = selectedId - 1;
                createSlector('#dr-front', drawer_j, 'drawer', def_idx);
            })
            resolve()
        }
        else
            resolve()

    })


}
function createColorDropDown(id, selectedId) {
    return new Promise(resolve => {
        window.getData('getDoorColor', id).then(colors => {
            if (colors) {
                buildJson(colors, 'color_name', 'doors/color/').then(res => {
                    let def_idx = 0
                    if (selectedId)
                        def_idx = selectedId - 1;
                    createSlector('#dr-colors', res, 'colors', def_idx);
                })
                resolve()
            }
            else
                resolve()
        })
    })

}
function createDDforAddOns() {
    return new Promise(resolve => {
        let addOnsItem = {}
        window.getData('getAddons').then(addons => {

            addons.forEach(res => {
                if (!addOnsItem[res.group_name])
                    addOnsItem[res.group_name] = [];
                addOnsItem[res.group_name].push(res)
            })
            Object.keys(addOnsItem).forEach(key => {
                addOnsItem[key].unshift({ short_desc: 'None', image_path: '' });
                buildJson(addOnsItem[key], 'short_desc', 'add_ons/').then(res => {
                    let def_idx = 0
                    if (selected_doorConfig[key].idx + 1)
                        def_idx = selected_doorConfig[key].idx;
                    createSlector('#ad-' + key, res, key, def_idx);
                })
            })
            resolve()
        })
    })

}
function createDDforHardwareItems() {
    let model = $("#door-config-modal");

    return new Promise(resolve => {
        let hardwareItem = {}
        window.getData('getHardwares').then(hardware => {

            hardware.forEach(res => {
                if (!hardwareItem[res.group_name])
                    hardwareItem[res.group_name] = [];
                hardwareItem[res.group_name].push(res)
            })
            Object.keys(hardwareItem).forEach(key => {
                hardwareItem[key].unshift({ short_desc: 'None', image_path: '' });
                buildJson(hardwareItem[key], 'short_desc', 'hardware/').then(res => {
                    let def_idx = 0
                    if (selected_doorConfig[key].idx + 1)
                        def_idx = selected_doorConfig[key].idx;
                    createSlector('#ad-' + key, res, key, def_idx);
                })
            })
            model.show()
            // alert('hell')

            resolve()
        })
    })
}
var imgSrc = 'images/models/thumbnails/door_config/';
function buildJson(data, name, sub_path) {

    let dump = []
    return new Promise(resolve => {
        let disabled = false;
        if (data.length == 1)
            disabled = true;

        data.forEach((res, idx) => {
            res['idx'] = idx
            if (name.indexOf('color') != -1)
                res['pattern_path'] = imgSrc + sub_path + res.image_path
            let item = {
                text: res[name],
                value: res,
                description: '',
                disable: disabled,
                imageSrc: imgSrc + sub_path + (res.image_path ? res.image_path : 'no-image.jpg')
            }
            dump.push(item)
        })
        resolve(dump)
    })

}
function createSlector(id, ddata, type, def_idx) {
//    console.log('createSle', id, ddata, type)
    $(id).ddslick('destroy')
    $(id).ddslick({
        data: ddata,
        defaultSelectedIndex: def_idx,
        imagePosition: "left",
        onSelected: function (data) {
            selected_doorConfig[type] = data.selectedData.value;
            if (type == 'material')
                createDropdownDoor(data.selectedData.value.mat_type)
            if (type == 'doors') {
            $('#fetching-container').show();
                setTimeout(_=>{
                    let drawerArr = JSON.parse(data.selectedData.value.dr_front_has);
                    let Idx = 0;
                    if (drawerArr.length)
                        drawerArr.unshift({ name: 'None', image_path: '' })
                    else
                        drawerArr.unshift({ name: 'No Option', image_path: '' })
                    if (selected_doorConfig.drawer.idx + 1)
                        Idx = selected_doorConfig.drawer.idx + 1
                    createDrawerDropDown(drawerArr, Idx).then(_ => {
                        if (selected_doorConfig.colors.idx + 1)
                            Idx = selected_doorConfig.colors.idx + 1;
                        createColorDropDown(data.selectedData.value.prod_id, Idx).then(_ => {
                            console.log('glob_matadata.group',glob_matadata.group)
                            if(glob_matadata.group!='Add Ons'){
                                $('#dr-front').parent().show();
                                if (selected_doorConfig.handle_fin.idx + 1)
                                    Idx = selected_doorConfig.handle_fin.idx + 1;
                                createDDforHandFinish(Idx)
                            }
                            else
                            {
                               
                               $('#dr-front').parent().hide();
                                let model = $("#door-config-modal");
                                model.show();
                                $("#fetching-container").hide();
                            }
                          
                        })
                    })
                },100)
             
            }
            if (type == 'handle_fin') {
                let Idx = 0;
                if (selected_doorConfig.handle_col.idx + 1)
                    Idx = selected_doorConfig.handle_col.idx + 1;
                createDDforHandleColor(data.selectedData.value.item_id, Idx).then(_ => {
                    createDDforAddOns().then(_ => {
                        createDDforHardwareItems().then(_ => {
                            $("#fetching-container").hide();
                        })
                    })
                })
            }
            buttonStateforSave()
            $('#door-config-modal .close').on('click', function () {
                $('#door-config-modal').hide();

            })
//            console.log('sel', selected_doorConfig);
        }
    });
}
function saveConfig(type = '') {
    let metadata = glob_matadata
//    console.log('meta', glob_matadata, selected_doorConfig);
    let config = JSON.parse(JSON.stringify(selected_doorConfig))
//    config.colors['image_url'] = "images/models/textures/" + selected_doorConfig.material.mat_name.toLowerCase() + "/" + selected_doorConfig.colors.image_path;
    if (type == 'update') {
        let apply_all = $('#set-all-config input').is(':checked')
        let groupname = selectedItem.metadata.group;
        let type='group';
        let name=groupname;
        // if(configWall=='')
        // groupname=
        if (configWall != '')
            groupname = configWall;
        if(groupname=='Add Ons'){
            type='itemName';
            name=selectedItem.metadata.itemName;
        }
//        console.log('grpname', groupname, selectedItem.metadata.group, configWall)
        if (apply_all) {
            let items = B3D.model.scene.getItems();

            if (items.length) {
                items.forEach(res => {
                    if (res.metadata[type] == name) {
                        res.metadata.door_config = config;
                        res['prev_textureUrl'] = res.textureUrl;
                        res['prev_textureName'] = res.textureName;
                        res.textureUrl = config.colors.pattern_path;
                        res.textureName = config.colors.color_name;
                        res.updateTexture(res, config.colors.pattern_path, config.colors.color_name);
                        setTimeout(function () {
                            detectchanges(res, 'apply-all');
                        }, 500)
                    }
                })
            }
            $('#set-all-config input').prop('checked', false)
            configWall = groupname;
        }
        else {
            if (selectedItem && configWall == '') {
                console.log('prev')
                selectedItem.metadata.door_config = config;
                selectedItem['prev_textureUrl'] = selectedItem.textureUrl;
                selectedItem['prev_textureName'] = selectedItem.textureName;
                selectedItem.textureUrl = config.colors.pattern_path;
                selectedItem.textureName = config.colors.color_name;
                console.log(config.colors.image_url, config.color_name)
                selectedItem.updateTexture(selectedItem, config.colors.pattern_path, config.colors.color_name);
                setTimeout(function () {
                    detectchanges(selectedItem, 'color')
                }, 500)
            }
        }

        // console.log('set_dr_flag', set_dr_flag, metadata)
    }
    else {
        metadata['door_config'] = config;
        addItems(metadata, glob_temp);
    }
    if (metadata.group || configWall) {
//        console.log('metadata.group', metadata.group, configWall)
        if (metadata.group == 'Wall Cabinets' || configWall == 'Wall Cabinets') {
//            console.log('********1***********', config)
            set_dr_flag["Wall Cabinets"] = config;
        }
        else if(metadata.group == 'Add Ons' || configWall == 'Add Ons'){
            set_dr_flag["Add Ons"] = config;
            glob_matadata = {}
        }
        else {
            set_dr_flag["Tall Cabinets"] = config;
            set_dr_flag["Base Cabinets"] = config;
            set_dr_flag["Sink Cabinets"] = config; 
//            console.log('metax', set_dr_flag)
        }
    }


    // console.log('set_dr_flag', set_dr_flag, metadata)




    $("#door-config-modal").hide()

    $('#saveConfig').hide()
    $('#set-all-config').hide()
    configWall = '';

}
function buttonStateforSave() {
//    console.log(selected_doorConfig.material, selected_doorConfig.doors, selected_doorConfig.colors)
    if (selected_doorConfig.material && selected_doorConfig.doors && selected_doorConfig.colors) {
        $('#saveConfig').prop('disabled', false);
        $('#updateConfig').prop('disabled', false);
    }

    else {
        $('#saveConfig').prop('disabled', true);
        $('#updateConfig').prop('disabled', true);
    }



}


function addItems(metadata, temp,) {
    // $('#fetching-container').show();
    let pos = { x: 0, y: 0, z: 0 };
    let param = { itemName: metadata.itemName, group: metadata.group, halfsize: { x: metadata.def_sizes.width / 2, y: metadata.def_sizes.height / 2, z: metadata.def_sizes.depth / 2 } }
    var wallInfo = JSON.parse($('#change-wall').val())
    if (["Appliances"].indexOf(metadata.group) == -1) {
        let textureInfo = {}
        if (metadata.group == 'Base Cabinets' || metadata.group == 'Tall Cabinets' || metadata.group == 'Wall Cabinets' || metadata.group =="Sink Cabinets" || metadata.group =="Add Ons")
            textureInfo = {
                textureUrl: metadata.door_config.colors.pattern_path,
                textureName: metadata.door_config.colors.color_name
            };
        B3D.model.buildPosition(pos, param).then(buildpos => {
            let checkforBound = window.checkforBound(buildpos.box)
//            console.log('buildposs', checkforBound)
            if (!buildpos.isInter && checkforBound) {
                B3D.model.scene.addItem(metadata.itemType, metadata.modelUrl, metadata, textureInfo, buildpos.pos).then(_=>{
//                    console.log('selectedItem',selectedItem)
                    detectchanges(selectedItem, 'add')
                    saveDesigns('auto-save');
                    jsonstackindex++;
                    setButtonState();
                    // $('#fetching-container').hide();
                })
                // setTimeout(function () {
                //     console.log('selectedItem', selectedItem)
                //     detectchanges(selectedItem, 'add')
                //     saveDesigns('auto-save');
                //     jsonstackindex++;
                //     setButtonState()
                // }, 500)
                // window.getColors(itemType)

                if (temp.parent("div").parent("div")[0].id != "add-standard-items")
                    $("#cart_count").text(Number($("#cart_count").text()) + 1);
            } else {
                alert('No space in  ' + wallInfo.name + '. Unable to add the catalog item ' + metadata.group + '-' + metadata.itemName);
                // $('#fetching-container').hide();
            }
        })
        glob_matadata = {}

    }
    else {
        let halfsize = { x: metadata.def_sizes.width / 2, y: metadata.def_sizes.height / 2, z: metadata.def_sizes.depth / 2 }
        B3D.model.buildRoomItemPos(halfsize, '').then(box => {
            if (!box.isInter) {
                B3D.model.scene.addItem(metadata.itemType, metadata.modelUrl, metadata, {}, box.pos).then(_=>{

                })
            }
            else
                alert('No Space. Unable to add appliances in the room.')
                $('#fetching-container').hide();
        })

    }


}
function getColors(itemType) {
    if (itemType != 8 && itemType != 3) {
        $("#colorOptions").show();
        window.getData('colors', 'colors.json').then(col => {
            let color_html = ''
            col.forEach((cl, i) => {
                color_html += '<ul class="' + (i == 0 ? 'active' : '') + '"><h3><i class="glyphicon glyphicon-minus"></i><i class="glyphicon glyphicon-plus"></i> &nbsp;' + cl.name + '</h3>';

                if (cl.items[0]['group']) {
                    cl.items.forEach(cc => {
                        color_html += '<li class="group_label">' + cc.group + '</li>';
                        cc.items.forEach(color => {
                            color_html += '<li class="thumbnail add-item">'
                            color_html += '<img src=' + color.url + '> <span >' + color.name + '</span></li>'
                        });
                    });
                } else {
                    cl.items.forEach(color => {
                        color_html += '<li class="thumbnail add-item">'
                        color_html += '<img src=' + color.url + '> <span>' + color.name + '</span></li>'
                    });
                }

                color_html += '</ul>';
            })
            color_html += '<div style="display: none;" id="set-colors"><input type="checkbox"  /> <span></span></div>';

            $("#colorOptions .panel-body").html(color_html);
            initClickColors()
        });
    }
}

function initClickColors() {
    $("#colorOptions h3").click(function () {
        let has_class = $(this).parent("ul").hasClass("active");
        $("#colorOptions ul").removeClass("active");

        if (!has_class)
            $(this).parent("ul").addClass("active");
    })

    $("#clear_color").click(function () {
        selText = "None";
        textureImg = "images/models/textures/transparent.png";
        selectedItem['prev_textureUrl'] = selectedItem.textureUrl;
        selectedItem['prev_textureName'] = selectedItem.textureName;
        selectedItem.textureUrl = textureImg;
        selectedItem.textureName = selText;
        selectedItem.updateTexture(selectedItem, textureImg, selText);
        // B3D.model.floorplan.update();
        //        $('#set-colors').hide()
    })

    $("#colorOptions li").not('.group_label').click(function () {
        if ($('#set-colors').show()) {
            $("#set-colors input").prop('checked', false);
        }
        selText = $(this).text();
        textureImg = $(this).find('img').attr('src');
        selectedItem['prev_textureUrl'] = selectedItem.textureUrl;
        selectedItem['prev_textureName'] = selectedItem.textureName;
        selectedItem.textureUrl = textureImg;
        selectedItem.textureName = selText;
        selectedItem.updateTexture(selectedItem, textureImg, selText);
        $('#set-colors').show()

        $("#set-colors span").text('Apply for all ' + selectedItem.metadata.group)
        setTimeout(function () {
            detectchanges(selectedItem, 'color')
        }, 500)
    });

    $("#set-colors input").click(function () {
        var checked = $(this).prop('checked');

        let items = B3D.model.scene.getItems();
        items.forEach(res => {
            if (res.metadata.group == selectedItem.metadata.group && res.uuid != selectedItem.uuid) {
                res['prev_textureUrl'] = res.textureUrl;
                res['prev_textureName'] = res.textureName;
                // res['apply_clr_id'] = apply_id;
                res.textureUrl = textureImg;
                res.textureName = selText;
                res.updateTexture(res, textureImg, selText);
                setTimeout(function () {
                    detectchanges(res, 'apply-all');
                }, 500)

            }
        })
    })
}
/*
function initDeleteDesign(evt, ele) {

    evt.stopPropagation();
    let cur_ele = ele;
    let confirm_act = confirm("Are you sure, you want to delete this design?.");
    if (confirm_act) {
        $.ajax({
            url: "ajax_process.php",
            type: "POST",
            dataType: "json",
            data: { "action": "deleteDesign", "id": cur_ele[0].id },
            async: false,
            success: function (data) {
                cur_ele.parent("li").remove();
            },
            error: handleAjaxError
        });
    }
    // })
}
*/
function initDeleteDesign() {
    $('#view-mydesign span.delete').on("click", function (e) {
        e.stopPropagation();
        let cur_ele = $(this);
        let confirm_act = confirm("Are you sure, you want to delete this design?.");
        if (confirm_act) {
            $.ajax({
                url: api_url+"ajax_process.php",
                type: "POST",
                dataType: "json",
                data: { "action": "deleteDesign", "id": cur_ele[0].id },
                async: false,
                success: function (data) {
                    cur_ele.parent("li").remove();
                },
                error: handleAjaxError
            });
        }
    })
}


/*
 * Change floor and wall textures
 */

var TextureSelector = function (blueprint3d, sideMenu) {

    var scope = this;
    var three = blueprint3d.three;
    var isAdmin = isAdmin;

    var currentTarget = null;

    function initTextureSelectors() {
        $(".texture-select-thumbnail").click(function (e) {
            var textureUrl = $(this).attr("texture-url");
            var textureStretch = ($(this).attr("texture-stretch") == "true");
            var textureScale = parseInt($(this).attr("texture-scale"));
            currentTarget.setTexture(textureUrl, textureStretch, textureScale);

            e.preventDefault();
        });
    }

    function init() {
        three.wallClicked.add(wallClicked);
        three.floorClicked.add(floorClicked);
        three.itemSelectedCallbacks.add(reset);
        three.nothingClicked.add(reset);
        sideMenu.stateChangeCallbacks.add(reset);
        initTextureSelectors();
    }

    function wallClicked(halfEdge) {
        currentTarget = halfEdge;
        $("#floorTexturesDiv").hide();
        $("#wallTextures").show();
    }

    function floorClicked(room) {
        currentTarget = room;
        $("#wallTextures").hide();
        $("#floorTexturesDiv").show();
    }

    function reset() {
        $("#wallTextures").hide();
        $("#floorTexturesDiv").hide();
    }

    init();
}



/*
 * Floorplanner controls
 */

var ViewerFloorplanner = function (blueprint3d) {

    var canvasWrapper = '#floorplanner';

    // buttons
    var move = '#move';
    var remove = '#delete';
    var draw = '#draw';

    var activeStlye = 'btn-primary disabled';

    this.floorplanner = blueprint3d.floorplanner;
    //    console.log("blueprint3d", blueprint3d);
    var scope = this;

    function init() {

        $(window).resize(scope.handleWindowResize);
        scope.handleWindowResize();

        // mode buttons
        scope.floorplanner.modeResetCallbacks.add(function (mode) {
            $(draw).removeClass(activeStlye);
            $(remove).removeClass(activeStlye);
            $(move).removeClass(activeStlye);
            $("#wall-ht").hide();

            if (mode == BP3D.Floorplanner.floorplannerModes.MOVE) {
                $(move).addClass(activeStlye);
            } else if (mode == BP3D.Floorplanner.floorplannerModes.DRAW) {
                $(draw).addClass(activeStlye);
                $("#wall-ht").show();
            } else if (mode == BP3D.Floorplanner.floorplannerModes.DELETE) {
                $(remove).addClass(activeStlye);
            }

            $("#selected_mode #selected").html($("#wall_options li.btn-primary.disabled").html());
            $("#wall_options").hide();

            if (mode == BP3D.Floorplanner.floorplannerModes.DRAW) {
                $("#draw-walls-hint").show();
                scope.handleWindowResize();
            } else {
                $("#draw-walls-hint").hide();
            }
        });

        $(move).click(function () {
            scope.floorplanner.setMode(BP3D.Floorplanner.floorplannerModes.MOVE);
        });

        $(draw).click(function () {
            scope.floorplanner.setMode(BP3D.Floorplanner.floorplannerModes.DRAW);
        });

        $(remove).click(function () {
            scope.floorplanner.setMode(BP3D.Floorplanner.floorplannerModes.DELETE);
        });
    }

    this.updateFloorplanView = function () {
        scope.floorplanner.reset();
    }

    this.handleWindowResize = function () {
        $(canvasWrapper).height(window.innerHeight - $(canvasWrapper).offset().top);
        scope.floorplanner.resizeView();
    };

    init();
};

var mainControls = function (blueprint3d) {
    var blueprint3d = blueprint3d;

    function newDesign() {
        blueprint3d.model.loadSerialized('{"floorplan":{"corners":{"f90da5e3-9e0e-eba7-173d-eb0b071e838e":{"x":204.85099999999989,"y":289.052},"da026c08-d76a-a944-8e7b-096b752da9ed":{"x":672.2109999999999,"y":289.052},"4e3d65cb-54c0-0681-28bf-bddcc7bdb571":{"x":672.2109999999999,"y":-178.308},"71d4f128-ae80-3d58-9bd2-711c6ce6cdf2":{"x":204.85099999999989,"y":-178.308}},"walls":[{"corner1":"71d4f128-ae80-3d58-9bd2-711c6ce6cdf2","corner2":"f90da5e3-9e0e-eba7-173d-eb0b071e838e","frontTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0},"backTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0}},{"corner1":"f90da5e3-9e0e-eba7-173d-eb0b071e838e","corner2":"da026c08-d76a-a944-8e7b-096b752da9ed","frontTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0},"backTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0}},{"corner1":"da026c08-d76a-a944-8e7b-096b752da9ed","corner2":"4e3d65cb-54c0-0681-28bf-bddcc7bdb571","frontTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0},"backTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0}},{"corner1":"4e3d65cb-54c0-0681-28bf-bddcc7bdb571","corner2":"71d4f128-ae80-3d58-9bd2-711c6ce6cdf2","frontTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0},"backTexture":{"url":"rooms/textures/wallmap.png","stretch":true,"scale":0}}],"wallTextures":[],"floorTextures":{},"newFloorTextures":{}},"items":[]}');
    }

    function loadDesign() {
        files = $("#loadFile").get(0).files;
        var reader = new FileReader();
        reader.onload = function (event) {
            var data = event.target.result;
            blueprint3d.model.loadSerialized(data);
        }
        reader.readAsText(files[0]);
    }

    function saveDesign() {
        var data = blueprint3d.model.exportSerialized();
        var a = window.document.createElement('a');
        var blob = new Blob([data], {
            type: 'text'
        });
        a.href = window.URL.createObjectURL(blob);
        a.download = 'design.blueprint3d';
        document.body.appendChild(a)
        a.click();
        document.body.removeChild(a)
    }

    function DownloadAsImage() {
        var element = $("#viewer")[0];
        html2canvas(element, {
            onrendered: function (canvas) {
                var imageData = canvas.toDataURL("image/jpg");
                console.log('element',imageData)
                if (imageData) {
                    var newData = imageData.replace(/^data:image\/jpg/, "data:application/octet-stream");
                    var a = window.document.createElement('a');
                    a.href =newData;
                    a.download = 'image.jpg';
                    document.body.appendChild(a)
                    a.click();
                    document.body.removeChild(a)
                }
            }
        });
    }

    function init() {
        $("#new").click(newDesign);
        $("#loadFile").change(loadDesign);
        $("#saveFile").click(DownloadAsImage);
    }

    init();
}

/*
 * Initialize!
 */
var loadItemsList = function (id, items_obj, grouped = true) {
    var itemsDiv = $("#" + id);
    var html = '';

    if (!grouped)
        html = '<div class="items-wrapper row">';
    // console.log("items_obj", items_obj);

    if (items_obj.items && items_obj.items.length) {
        items_obj.items.forEach(group => {
            if (grouped) {
                if (group.default == 1)
                    html = '<div class="items-wrapper row active">';
                else
                    html = '<div class="items-wrapper row">';
            }

            html += '<h3><i class="glyphicon glyphicon-minus"></i><i class="glyphicon glyphicon-plus"></i> &nbsp;' + group.name + '</h3>';
            for (var i = 0; i < group.items.length; i++) {
                var item = group.items[i];
                var item_status = (item.status == 0 ? 0 : 1);

                html += '<a class="thumbnail add-item' + (item_status == 0 ? " inactive" : "") + '" model-name="' +
                    item.name +
                    '" model-url="' +
                    item.model +
                    '" model-type="' +
                    item.type +
                    '"model-img="' +
                    item.image +
                    '"model-grp="' +
                    group.name +
                    '"model-price="' +
                    item.price +
                    '"model-materialcost="' +
                    item.material_cost +
                    '"model-laborcost="' +
                    item.labor_cost +
                    '"model-status="' +
                    item_status +
                    '"model-uom="' +
                    item.uom +
                    '"model-measurements="' +
                    btoa(JSON.stringify(item.measurements)) +
                    '"model-def_sizes="' +
                    btoa(JSON.stringify(item.def_sizes)) +
                    '"model-sku="' +
                    item.sku + ' "><img src="' +
                    item.image +
                    '" alt="Add Item"> <span>' +
                    item.name +
                    '</span></a>';
            }
            if (grouped) {
                html += '</div>';
                itemsDiv.append(html);
            }
        })
    }

    if (!grouped) {
        html += '</div>';
        itemsDiv.append(html);
    }
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    let res = "";
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            res = c.substring(name.length, c.length);
        }
    }
    return res;
}
function deleteCookie(kname) {
    //  document.cookie = kname +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = kname + "=;";
}

function setMeasurementValue(type, val) {
    //    console.log('typed',type)
    if (selectedItem != null) {
        if (type == 'IN') {
            $("#item-width").val(decToFracPlain(window.cmToIn(selectedItem.getWidth()).toFixed(4)));
            $("#item-height").val(decToFracPlain(window.cmToIn(selectedItem.getHeight()).toFixed(4)));
            $("#item-depth").val(decToFracPlain(window.cmToIn(selectedItem.getDepth()).toFixed(4)));
            $(".display-inch").show();
            $(".display-ft").hide();

        }
        else if (type == 'FT') {
            window.cmToFtIN(selectedItem.getWidth())
            var width = (window.cmToFtIN(selectedItem.getWidth())).split("'")
            var height = (window.cmToFtIN(selectedItem.getHeight())).split("'")
            var depth = (window.cmToFtIN(selectedItem.getDepth())).split("'")
            // console.log( 'tes',test[0].replace(/["']/g, ""))
            $("#item-width-ft").val(Number(width[0].replace(/["']/g, "")));
            $("#item-height-ft").val(Number(height[0].replace(/["']/g, "")));
            $("#item-depth-ft").val(Number(depth[0].replace(/["']/g, "")));
            $("#item-width-in").val(Number(width[1].replace(/["']/g, "")));
            $("#item-height-in").val(Number(height[1].replace(/["']/g, "")));
            $("#item-depth-in").val(Number(depth[1].replace(/["']/g, "")));
            $("#measure-text").text('Measurement In Feet');
            $(".display-inch").hide();
            $(".display-ft").show();

        }
    }


    //    measureType=type;
}
function cmToIn(cm) {
    // console.log('cm',cm)
    return cm / 2.54;
}

function inToCm(inches) {
    // console.log('inch',inches)
    return inches * 2.54;
}
function cmToFt(inch) {
    // console.log('in',inch)
    return inch / 30.48;
}
function inToFt(inch) {
    // console.log('ft',inch)
    return inch / 12;
}
function cmToFtIN(cm) {
    //     console.log('cm',cm)
    var realFeet = ((cm * 0.393700) / 12);
    var feet = Math.floor(Math.abs(realFeet));
    var inches = Math.round((realFeet - feet) * 12);
    // console.log(realFeet, feet, inches);
    return feet + "'" + inches + '"';
}

function ftToCm(ft) {
    // console.log('ft',ft)
    return ft * 30.48;
}

function initMeasureOnclik(blueprint3d) {


    $('#uom_container select').on('change', function () {
        window.setMeasurementValue(this.value)
        APP_UOM = this.value;
        setWallSizeVal();

        blueprint3d.floorplanner.resizeView1();
        //		$("#show_measure").trigger("click");
        window.prepareSummmaryHTML(blueprint3d, this.value).then(grouped_items => {

            console.log('getPrice', grouped_items)
            var html = grpdQty(grouped_items);
            if (sum_type == 'summary-viewer')
                loadPriceSummary();

            $('#' + sum_type + ' #content').html(html);

        })
        blueprint3d.three.wallClicked.fire();
        window.buildWallInfoHTML($('#change-wall').val())
    });


    //  



}
function loadPreStyle(selected_style, blueprint3d) {
    let file_name = "default" + (selected_style != "" ? ("-" + selected_style) : "") + ".json?v2";
    $.getJSON("json/" + file_name, function (data) {
        setCartCount(data);

        var data_str = JSON.stringify(data);
        blueprint3d.model.loadSerialized(data_str);

        $("#update-floorplan").trigger("click");
    });

}

function loadMyDesign(end_url, path) {

    console.log("endurl", end_url, path)
    $.getJSON(path + end_url + ".json", function (data) {
        console.log('enddat', data,)
        setCartCount(data);

        var data_str = JSON.stringify(data);
        B3D.model.loadSerialized(data_str);

        $("#update-floorplan").trigger("click");
    });
}

function setCartCount(data) {
    let count = 0;
    if (data.items) {
        data.items.forEach(item => {
            if (["Appliances", "Openings"].indexOf(item.name) == -1)
                count += item.items.length;
        })
    }
    $("#cart_count").text(count);
}

var grouped_items = {};
function prepareSummmaryHTML(blueprint3d) {
    return new Promise(resolve => {
        grouped_items = {};
        var sum_items = []
        sum_items = blueprint3d.model.scene.getItems();
        // console.log('sum_items',sum_items)
        // let measureType= $('#uom_container').val();
        // var ctr =0
        selected_doorConfig
        sum_items.forEach(res => {
            let grp_name = res.metadata.group;

            if (["Openings", "Appliances"].indexOf(grp_name) == -1) {
                let buildData = {
                    name: res.metadata.itemName,
                    group: grp_name,
                    sku: res.metadata.sku,
                    image_url: res.metadata.imageUrl,
                    width: APP_UOM == 'IN' ? decToFracPlain(window.cmToIn((res.halfSize.x * 2)).toFixed(4)) : window.cmToFtIN((res.halfSize.x * 2).toFixed(2)),
                    height: APP_UOM == 'IN' ? decToFracPlain(window.cmToIn((res.halfSize.y * 2)).toFixed(4)) : window.cmToFtIN((res.halfSize.y * 2).toFixed(2)),
                    depth: APP_UOM == 'IN' ? decToFracPlain(window.cmToIn((res.halfSize.z * 2)).toFixed(4)) : window.cmToFtIN((res.halfSize.z * 2).toFixed(2)),
                    price: res.metadata.price,
                    material_cost: res.metadata.material_cost,
                    labor_cost: res.metadata.labor_cost,
                    uom: res.metadata.uom,
                    measurements: res.metadata.measurements,
                    def_sizes: res.metadata.def_sizes,
                    xpos: res.position.x,
                    ypos: res.position.y,
                    zpos: res.position.z,
                    door_mat: hasDoorconfig(res)?res.metadata.door_config.material.mat_name:'',
                    door_style: hasDoorconfig(res)?res.metadata.door_config.doors.long_desc:'',
                    door_col: hasDoorconfig(res)?res.metadata.door_config.colors.color_name:'',
                    drawer_front:hasDoorconfig(res)? res.metadata.door_config.drawer.name:'',
                    handle_col: hasDoorconfig(res)?res.metadata.door_config.handle_col.short_desc:'',
                    corbel: hasDoorconfig(res)?res.metadata.door_config.corbels.short_desc:'',
                    molding: hasDoorconfig(res)?res.metadata.door_config.moldings.short_desc:'',
                    valance:hasDoorconfig(res)? res.metadata.door_config.valances.short_desc:"",
                    floating_shelf: hasDoorconfig(res)?res.metadata.door_config.floating_shelf.short_desc:'',
                    misc_item:hasDoorconfig(res)? res.metadata.door_config.misc_item.short_desc:'',
                }
                console.log('wisth',window.cmToIn((res.halfSize.x * 2)).toFixed(2))
                buildData['conf'] = this.buildSumData(buildData);
                window.getData('get_price', '', { name: buildData.name, width:decToFracPlain(window.cmToIn((res.halfSize.x * 2)).toFixed(4)), height: decToFracPlain(window.cmToIn((res.halfSize.y * 2)).toFixed(4)) }).then(res => {
                    console.log('res', res)
                    if (res.length) {
                        buildData.price = res[0].retail;
                        buildData.sku = res[0].sku;
                    }
                    if (!grouped_items[grp_name])
                        grouped_items[grp_name] = [];
                    console.log('getPrice1', grouped_items)
                    grouped_items[grp_name].push(buildData);
                    resolve(grouped_items)
                    // ctr+=1;

                })

            }
            // console.log('array',ctr,sum_items.length)

        })

    })


}
function hasDoorconfig(res){
    let state=false;
    if(res.metadata.hasOwnProperty('door_config'))
    state=true;
return state;
}
function buildSumData(data) {
    let res = { door: {}, hardware: {}, add_ons: {} }
    res.door = { material: data.door_mat, style: data.door_style, drawer_front: data.drawer_front, color: data.door_col }
    res.hardware = { handle: data.handle_col, others: data.misc_item }
    res.add_ons = { corbel: data.corbel, valance: data.valance, floating_shelf: data.floating_shelf, molding: data.molding }
    return res

}
function closeDragalert() {
    $('#drag-alert-modal').hide()
}
function grpdQty(grouped_items) {
    console.log('grouped_items', grouped_items)
    var html = '';
    price.items = [];

    Object.keys(grouped_items).forEach(grp => {
        html += '<div class="group-container"> <h3>' + grp + '</h3>';
        let qty = 1;
        let total = 0;
        let prev_item = {};

        grouped_items[grp].forEach(sum_item => {
            if (sum_type == 'prev_summary') {
                if (Object.keys(prev_item).length && ((prev_item.sku + prev_item.width + prev_item.height + prev_item.depth != sum_item.sku + sum_item.width + sum_item.height + sum_item.depth) || (prev_item.door_mat != sum_item.door_mat || prev_item.door_style != sum_item.door_style || prev_item.door_col != sum_item.door_col || prev_item.drawer_front != sum_item.drawer_front || prev_item.handle_col != sum_item.handle_col || prev_item.misc_item != sum_item.misc_item || prev_item.corbel != sum_item.corbel || prev_item.molding != sum_item.molding || prev_item.floating_shelf != sum_item.floating_shelf || prev_item.valance != sum_item.valance))) {
                    console.log('prev_item', prev_item)
                    let res = btoa(JSON.stringify(prev_item))
                    // if(Object.keys(prev_item).length){
                    html += '<div class="prev-sum-body"><img src="' + (prev_item.image_url ? prev_item.image_url : "") +
                        '"><div class="details"><label>Name:</label> ' + prev_item.name +
                        ' <br> <label>SKU:</label> ' + prev_item.sku +
                        '<br> <label>W:</label> ' + prev_item.width + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                        '<label>H:</label> ' + prev_item.height + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                        '<label>D:</label> ' + prev_item.depth + (APP_UOM == 'IN' ? ' In. ' : ' ') ;
                        if(sum_item.group!='Add Ons') 
                        html += '<br><label>Style:</label> ' + prev_item.door_style +' | <label>Color:</label> ' + prev_item.door_col ;
                        html += '<br><div class="pricer"> <label>Qty:</label> ' + (qty - 1) + ' | <label>Price:</label> $' + Number(qty - 1) * Number(prev_item.price) ;
                        if(sum_item.group!='Add Ons')
                        html +=   '<a data-item=' + res + ' onClick=showMore("' + res + '",' + qty + ')>More</a>'
                        html +=  '</div></div></div>';
                    qty = 1;
                    // }

                    // count+=qty;
                }

                if (grouped_items[grp][grouped_items[grp].length - 1] == sum_item) {
                    let res = btoa(JSON.stringify(sum_item))
                    console.log()

                    html += '<div class="prev-sum-body"><img src="' + (sum_item.image_url ? sum_item.image_url : "") +
                        '"><div class="details"><label>Name:</label> ' + sum_item.name +
                        '<br> <label>SKU:</label> ' + sum_item.sku +
                        '<br> <label>W:</label> ' + sum_item.width + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                        '<label>H:</label> ' + sum_item.height + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                        '<label>D:</label> ' + sum_item.depth + (APP_UOM == 'IN' ? ' In. ' : ' ') ;
                        if(sum_item.group!='Add Ons')
                        html += '<br><label>Style:</label> ' + sum_item.door_style +' | <label>Color:</label> ' + sum_item.door_col +'<br>';

                        html += '<div class="pricer"> <label>Qty:</label> ' + qty + ' | <label>Price:</label> $' + (Number(qty) * Number(sum_item.price)).toFixed(2);
                        if(sum_item.group!='Add Ons')
                        html += '<a onClick=showMore("' + res + '",' + qty + ')>More</a>'
                        html += '</div></div></div>';
                    qty = 0;
                }
            } else {
                if (Object.keys(prev_item).length && ((prev_item.sku + prev_item.width + prev_item.height + prev_item.depth != sum_item.sku + sum_item.width + sum_item.height + sum_item.depth) || (prev_item.door_mat != sum_item.door_mat || prev_item.door_style != sum_item.door_style || prev_item.door_col != sum_item.door_col || prev_item.drawer_front != sum_item.drawer_front || prev_item.handle_col != sum_item.handle_col || prev_item.misc_item != sum_item.misc_item || prev_item.corbel != sum_item.corbel || prev_item.molding != sum_item.molding || prev_item.floating_shelf != sum_item.floating_shelf || prev_item.valance != sum_item.valance))) {
                    if (Object.keys(prev_item).length) {
                        html += '<div class="sum-body"><img src="' + (prev_item.image_url ? prev_item.image_url : "") +
                            '"><div class="details"><label>Name:</label> ' + prev_item.name +
                            '<br> <label>Description:</label> ' + prev_item.group +
                            '<br> <label>SKU:</label> ' + prev_item.sku +
                            '<br> <label>W:</label> ' + prev_item.width + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                            '<label>H:</label> ' + prev_item.height + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                            '<label>D:</label> ' + prev_item.depth + (APP_UOM == 'IN' ? ' In. ' : ' ') ;
                        if(prev_item.group!='Add Ons')
                        {    '<h6>Door</h6><label>Material:</label> ' + prev_item.door_mat +
                            ' | <label>Style:</label> ' + prev_item.door_style +
                            ' | <label>Color:</label> ' + prev_item.door_col +'';
                            if (prev_item.drawer_front != 'None')
                                html += ' | <label>Drawer Front:</label> ' + sum_item.drawer_front + '';
                            html += ' <div style="display:none"><h6>Hardwares</h6> <label>Handle:</label> ' + sum_item.handle_col + '';
                            if (prev_item.misc_item != "None")
                                html += ' | <label>Handle:</label> ' + prev_item.handle_col + '';
                            if (prev_item.corbel != 'None' || prev_item.molding != 'None' || prev_item.floating_shelf != 'None' ||prev_item.valance != 'None') {
                                html += '<h6>Add-Ons</h6>'
                                if (prev_item.corbel != 'None')
                                    html += '<label>Corbel:</label>' + prev_item.corbel + '';
                                if (prev_item.floating_shelf != 'None')
                                    html += ' | <label>Floating Shelf:</label>' + prev_item.floating_shelf + '';
                                if (prev_item.molding != 'None')
                                    html += ' | <label>Molding:</label>' + prev_item.molding + '';
                                if (prev_item.valance != 'None')
                                    html += ' | <label>Molding:</label>' + prev_item.valance + ''
                            }
        
                            html += '</div>';
                            html += '<a style="margin-left:10px" class="more-rev">More</a><a style="display:none;margin-left:10px;"     class="hide-rev">Hide</a>' ;
                            }
                           
                            html += '</div><div class="pricer"><label>Qty:</label> ' + (qty-1) + '<br><label>Price:</label> $' + (Number(qty-1) * Number(prev_item.price)).toFixed(2) +
                            //                  '<br><br><span><a href="#" class="glyphicon glyphicon-pencil"></a> &nbsp; <a href="#" class="glyphicon glyphicon-trash"></a></span>'+
                            // '</div><div class="pricer"><label>Qty:</label> ' + (qty - 1) + '<b class="splitter"></b><a href="#" class="glyphicon glyphicon-trash"></a><br><br><label>Price:</label> $' + Number(qty - 1) * Number(prev_item.price) +
                            '</div></div>';
                        qty = 1;
                    }

                }

                if (grouped_items[grp][grouped_items[grp].length - 1] == sum_item) {
                    html += '<div class="sum-body"><img src="' + (sum_item.image_url ? sum_item.image_url : "") +
                        '"><div class="details"><label>Name:</label> ' + sum_item.name +
                        '<br> <label>Description:</label> ' + sum_item.group +
                        '<br> <label>SKU:</label> ' + sum_item.sku +
                        '<br> <label>W:</label> ' + sum_item.width + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                        '<label>H:</label> ' + sum_item.height + (APP_UOM == 'IN' ? ' In. ' : ' ') +
                        '<label>D:</label> ' + sum_item.depth + (APP_UOM == 'IN' ? ' In. ' : ' ') ;
                        if(sum_item.group!='Add Ons'){
                            '<h6>Door</h6><label>Material:</label> ' + sum_item.door_mat +
                            ' | <label>Style:</label> ' + sum_item.door_style +
                            ' | <label>Color:</label> ' + sum_item.door_col + ''
                        if (sum_item.drawer_front != 'None')
                            html += ' | <label>Drawer Front:</label> ' + sum_item.drawer_front + '';
                        html += ' <div style="display:none"><h6>Hardwares</h6> <label>Handle:</label> ' + sum_item.handle_col + '';
                        if (sum_item.misc_item != "None")
                            html += ' | <label>Handle:</label> ' + sum_item.handle_col + '';
                        if (sum_item.corbel != 'None' || sum_item.molding != 'None' || sum_item.floating_shelf != 'None' || sum_item.valance != 'None') {
                            html += '<h6>Add-Ons</h6>'
                            if (sum_item.corbel != 'None')
                                html += '<label>Corbel:</label>' + sum_item.corbel + '';
                            if (sum_item.floating_shelf != 'None')
                                html += ' | <label>Floating Shelf:</label>' + sum_item.floating_shelf + '';
                            if (sum_item.molding != 'None')
                                html += ' | <label>Molding:</label>' + sum_item.molding + '';
                            if (sum_item.valance != 'None')
                                html += ' | <label>Molding:</label>' + sum_item.valance + ''
                        }
                        html += '</div>';
                         html +='<a style="margin-left:10px" class="more-rev">More</a><a style="display:none;margin-left:10px;" class="hide-rev">Hide</a>' ;

                        }
                   html +=  '</div><div class="pricer"><label>Qty:</label> ' + qty + '<br><label>Price:</label> $' + (Number(qty) * Number(sum_item.price)).toFixed(2) +
                        //                  '<br><br><span><a href="#" class="glyphicon glyphicon-pencil"></a> &nbsp; <a href="#" class="glyphicon glyphicon-trash"></a></span>'+
                        // '</div><div class="pricer"><label>Qty:</label> ' + qty + '<b class="splitter"></b> <a href="#" class="glyphicon glyphicon-trash"></a><br><br><label>Price:</label> $' + Number(qty) * Number(sum_item.price) +
                        '</div></div>';
                    qty = 0;
                }
            }

            qty++;
            total += Number(sum_item.price);

            prev_item = sum_item;

        })
        html += '</div>';

        price.items.push({ label: grp, value: total });
    })

    return html;
}
function showMore(item, qty) {
    let res = JSON.parse(atob(item));
    let more_html = ''
    more_html = '<div class="modal-content"><span class="close">&times;</span><h2>' + res.group + '</h2><h4>General</h4><label>Name:</label> ' + res.name +
        '<br> <label>Description:</label> ' + res.group +
        '<br> <label>SKU:</label> ' + res.sku +
        '<br> <label>W:</label> ' + res.width + (APP_UOM == 'IN' ? ' In. ' : ' ') +
        '<label>H:</label> ' + res.height + (APP_UOM == 'IN' ? ' In. ' : ' ') +
        '<label>D:</label> ' + res.depth + (APP_UOM == 'IN' ? ' In. ' : ' ') +'<div style="float:right"><label>Qty:</label>' + qty + '</div>';
// if(res.group!='Add Ons'){
    more_html+='<h4>Door Config</h4>' +
    '<label>Material</label>:<span>' + res.door_mat +
    ' <br><label>Style</label>:<span > ' + res.door_style +
    '<br> <label>Color</label>:<span> ' + res.door_col + '</span>';
if (res.drawer_front != 'None')
    more_html += '<br> <label>Drawer Front</label>:<span > ' + res.drawer_front + '</span>';
more_html += '<h4>Harwares</h4><label>Handle:</label> ' + res.handle_col + '';
if (res.misc_item != 'None')
    more_html += ' | <label>Others:</label> ' + res.misc_item + '';

if (res.corbel != 'None' || res.molding != 'None' || res.floating_shelf != 'None' || res.valance != 'None') {
    more_html += '<h4>Add-Ons</h4>'
    if (res.corbel != 'None')
        more_html += '<label>Corbel:</label>' + res.corbel + '<br>';
    if (res.floating_shelf != 'None')
        more_html += '<label>Floating Shelf:</label>' + res.floating_shelf + '<br>';
    if (res.molding != 'None')
        more_html += '<label>Molding:</label>' + res.molding + '<br>';
    if (res.valance != 'None')
        more_html += '<label>Molding:</label>' + res.valance + ''
}
// }
 

    more_html += '</div>';


    $('#showmore-modal').html(more_html);
    $('#showmore-modal').show();
    $('#showmore-modal').click(() => { $('#showmore-modal').hide() })
    $('#showmore-modal .close').click(() => { $('#showmore-modal').hide() })

}
function loadPriceSummary() {
    price.total = 0;
    let other_charges = 0;
    // Price Summary Content
    var price_html = "<h3>Order Summary</h3><ul>";
    if (price.items.length) {
        price.items.forEach(row => {
            price_html += "<li class='l'>" + row.label + "</li><li class='r'>$ " + row.value.toFixed(2) + "</li>";
            price.total += row.value;
        });
        price_html += "<li class='hr'></li></li>";
    }

    if (price.others.length)
        price.others.forEach(row => {
            price_html += "<li class='l'>" + row.label + "</li><li class='r'>$ " + row.value.toFixed(2) + "</li>";
            other_charges += row.value;
        });

    price_html += "<li class='l'>ITEM SUBTOTAL</li><li class='r'> $ " + (price.total).toFixed(2) + "</li>";
    price_html += "<li class='l'>Other Charges</li><li class='r'> $ " + (other_charges).toFixed(2) + "</li>";
    price_html += "<li style='width:100%' class = 'r'><a id='apply-promo'>Apply Promotion</a></li>"
    price_html += "<li class='l discount'>Discount</li><li class='r discount'>( - )&nbsp;&nbsp; $ <span id='disc_holder'>" + price.discount.toFixed(2) + "</span></li>";
    price_html += "<li class='hr'></li>";
    price_html += "<li class='l res'>NET TOTAL</li><li class='r res'> $ <span id='price_holder'>" + (price.total + other_charges - price.discount).toFixed(2) + "</span></li>";
    price_html += "</ul>";

    price_html += "<ul><li style='width: 100%;'><button id='place-order' class='btn btn-default'>PLACE ORDER</button></li></ul>";

    price_html += "<div id='placeorder-modal'><div class='modal-content'> <span class='close'>&times;</span>  <div class='user-info'>";

    price_html += "<div id='new-cus'><h3>New Customer</h3><label>First Name</label><input type='text' id='cfname' required/><br/>";
    price_html += "<label>Last Name</label><input type='text' id='clname' required/><br/>";
    price_html += "<label>Email</label><input type='email' id='cmail-new' required/>";
    price_html += "<label>Address</label><input type='text' id='caddress' required/><br/>";
    price_html += "<label>City</label><input type='text' id='ccity' required/><br/>";
    price_html += "<label>State</label><input type='text' id='cstate' required/><br/>";
    price_html += "<label>Zipcode</label><input type='text' id='czip' required/><br/>";
    price_html += "<label>Phone</label><input type='text' id='cphone' required/><br/> </div>";

    price_html += "<div style='display:none' id='exist-cus'> <h3>Existing Customer</h3>";
    price_html += "<label>Email</label><input type='email' id='cmail-exist' required/><label class='error'></label><input type='button' value='Validate' id='validate-email' /></div>";

    price_html += " <input type='submit' value='Submit Order' id='submit-order' /><br/><div class='switch-cus'><label><input type='checkbox' id='switch-cust' /> Existing Customer</label></div>";
    price_html += "</div> </div></div>";
    price_html += "<div id='ord-detail-modal'><div class='modal-content'><span class='close'>&times;</span> <div class='user-info'>";
    price_html += "<span id='ord-num'></span><button onclick='closemodal()'>Ok</button></div></div></div>";
    price_html += "<div id='promo-modal'><div class='modal-content'><span class='close'>&times;</span><h2 style='text-allign:center;font-size:17px'>Apply Promotion</h2><li class='disc-info'><span>Discount By </span> <select id='disc-event'>"
    discount_arr.forEach((item, idx) => {
        if (idx == 0)
            price_html += "<option selected value='" + item.type + "'>" + item.name + "</option>"
        else
            price_html += "<option value='" + item.type + "'>" + item.name + "</option>"
    });

    price_html += "</select><input  id='disc-inp'  value ='0' /></li><button id='apply-promo-btn'> Apply Promo</button></div><//div>";


    $('#summary-viewer #price_summary').html(price_html);

    $(document).ready(function () {
        $('#apply-promo').click(function () {
            $('#promo-modal').show()
        })

        $('#promo-modal .close').click(function () {
            $('#promo-modal').hide();
        }
        )
        $('#apply-promo-btn').click(function () {
            let disc_filter = $('#disc-event').val();
            let val = $('#disc-inp').val();
            if (disc_filter == 'per')
                price.discount = price.total * (Number(val) / 100);
            else if (disc_filter == 'amnt')
                price.discount = Number(val)
            price.promo_val = Number(val)
            $('#disc_holder').text(price.discount.toFixed(2))
            $('#price_holder').text((price.total + other_charges - price.discount).toFixed(2));
            $('#promo-modal').hide();
        })
        $('#disc-event').on('change', function () {
            price.discount = 0
            $('#disc-inp').val(0);
            $('#disc_holder').text(price.discount.toFixed(2))
            $('#price_holder').text((price.total + other_charges - price.discount).toFixed(2));
            price.promo_type = $('#disc-event option:selected').text()
        })

        $('#disc-inp').keyup(function () {
            let cal_length = 2;
            let alrt_msg = 'Percentage'
            let replace = /[^0-9]/g;
            let disc_filter = $('#disc-event').val();
            if (disc_filter == 'amnt') {
                cal_length = 5;
                alrt_msg = 'Amount'
                replace = /[^0-9]./g
            }
            let val = $('#disc-inp').val().replace(replace, '');
            $(this).val(val)
            if (val.length > cal_length)
                alert(alrt_msg + ' length should not exceed greater than ' + cal_length + ' digit');
            if (price.total < Number(val) && disc_filter == 'amnt') {
                alert('Discount amount should not exceed the total price');
                let num_len = Math.round($(this).val());
                cal_length = `${num_len}`.length - 1;
            }
            $(this).val(function () { return val.replace(replace, '').substr(0, cal_length); });
        })
    })

    var modal = document.getElementById("placeorder-modal");

    // Get the button that opens the modal
    var btn = document.getElementById("place-order");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    $('#switch-cust').click(function () {
        var checked = $(this).prop('checked');
        if (checked) {
            cust_status = 'existing';
            $('#exist-cus .error').hide();
            $('#new-cus').hide();
            $('#exist-cus').show();
            $("#submit-order").hide();
        } else {
            cust_status = 'new';
            $('#new-cus').show();
            $('#exist-cus').hide();
            $("#submit-order").show();
        }
    })


    $('#cmail-exist').keyup(function () {
        $("#submit-order").hide();
        $('#validate-email').show();
        $('#exist-cus .error').html("").hide();
    });
    $('#validate-email').click(function () {
        let cust_info = {
            cemail: (cust_status === 'new') ? $('#cmail-new').val() : $('#cmail-exist').val(),
        }

        jQuery.ajax({
            type: "POST",
            url: api_url+'includes/placeorder.php',
            data: { 'action': 'validate_customer', 'custinfo': cust_info },
            dataType: 'json',
            async: false,

            success: function (data) {
                if (data.status == "valid") {
                    $('#exist-cus .error').html("").hide();
                    $('#validate-email').hide();
                    $("#submit-order").show();
                } else {
                    $('#exist-cus .error').html("Email id not exists !<br>Please make sure you have entered an valid existing customer email !").show();
                }
            }
        });
    });

    btn.onclick = function () {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    $('#submit-order').click(function () {

        let cust_info = {
            cfname: $('#cfname').val(),
            clname: $('#clname').val(),
            cemail: (cust_status === 'new') ? $('#cmail-new').val() : $('#cmail-exist').val(),
            caddress: $('#caddress').val(),
            ccity: $('#ccity').val(),
            cstate: $('#cstate').val(),
            czip: $('#czip').val(),
            cphone: $('#cphone').val()
        }
        //        console.log('cust_info', cust_info)
        let designData = B3D.model.exportSerialized();
        let imageData = window.sessionStorage.getItem('cartdesign');
        jQuery.ajax({
            type: "POST",
            url: api_url+'includes/placeorder.php',
            data: { 'action': cust_status, 'orderinfo': grouped_items, 'custinfo': cust_info, 'designdata': JSON.stringify(designData), 'imagedata': imageData, 'suminfo': price },
            // dataType:'json',
            async: false,

            success: function (data) {
                console.log('text', data)

                let res = JSON.parse(data);
                if (res.status == 'success') {
                    $('#placeorder-modal').hide();
                    $('#ord-detail-modal').show();
                    $('#ord-detail-modal .close').hide();
                    $('#ord-num').html('<h6>Your order was successfully placed!</h6><span>Your order reference # is <b>' + res.order_id + '</b></span>');
                } else if (res.status == 'not-exist') {
                    $('#placeorder-modal').hide();
                    $('#ord-detail-modal').show();
                    $('#ord-num').text('Unable to place your order. Kindly maksure the customer mail id : ' + res.cemail + ' is valid or try by adding the customer details');
                }
            }
        });

    })

    $("#prev_summary_handler").hide();
    $("#sidebar").show();
    $('#placeorder-modal .close').click(function(){
       $(' #placeorder-modal').hide();
    })
}

function closemodal() {
    $('#ord-detail-modal').hide();
    window.location.href = "logout";
}
var mes_info = []
function getMeasureInfo() {
    return new Promise(resolve=>{
        var wallItems = B3D.model.floorplan.getWalls();      
        if (wallItems.length) {
            mes_info = []
            let i = 1
            wallItems.forEach(wall => {
                buildAvailSpace( wall.height).then(occu_space=>{
                    // console.log('wallItems', wall,mes_info)
                    var tot_space = wall.backEdge ? wall.backEdge.interiorDistance() : wall.frontEdge.interiorDistance();
                    let buildWall_meas = {
                        id: wall.id,
                        name: 'Wall-' + i++,
                        base: occu_space.base,
                        wall: occu_space.wall,
                        total_space: tot_space,
                        center: wall.backEdge ? wall.backEdge.interiorCenter() : wall.frontEdge.interiorCenter(),
                        top: wall.height,
                        interiorEnd: wall.backEdge? wall.backEdge.interiorEnd():wall.frontEdge.interiorEnd(),
                        interiorStart: wall.backEdge? wall.backEdge.interiorStart():wall.frontEdge.interiorStart()
                    }
                    mes_info.push(buildWall_meas)
                    if(wallItems.length==i-1)
                         resolve(mes_info)
               
                })
                
            })
        }
    })
   
}
function buildAvailSpaceVar(){

    var wall_space = $('#wall-select');
    var html = 'Selected wall - <select id="change-wall">';
    mes_info.forEach((w_space, index) => {
        if (index == 1)
            html += '<option  selected value=' + JSON.stringify(w_space) + '>' + w_space.name + '</option>';
        else
            html += '<option  value=' + JSON.stringify(w_space) + '>' + w_space.name + '</option>';
    })

    html += '</select> - Info  <button onClick="editWallht()" class="btn edit-wall" > <span class="glyphicon glyphicon-edit"></span></button>';
    wall_space.html(html);
    let setWallname = {}
    if (wallID == '') {
        setWallname = JSON.parse($('#change-wall').val())
        window.buildWallInfoHTML($('#change-wall').val());
        wallID = setWallname.id
        B3D.three.setWallName(setWallname);

    } else {
        mes_info.forEach(mes => {
            if (mes.id == wallID) {
                // console.log('mes', mes)
                setWallname = JSON.stringify(mes)
                $('#change-wall').val(setWallname)
                window.buildWallInfoHTML(setWallname)
                wallID = mes.id
                setWallname = JSON.parse(JSON.stringify(mes))
                B3D.three.setWallName(setWallname);

            }
        })
    }

    $('#change-wall').on('change', function () {
        setWallname = JSON.parse($('#change-wall').val())
        window.buildWallInfoHTML($('#change-wall').val())

        wallID = setWallname.id;
        B3D.three.setWallName(setWallname);

        let html = ''
        html += '<hr style="color:black">'
        $('#change-wall').next('hr').show();
        getMeasureInfo().then(_=>{
        buildAvailSpaceVar();    
        });
    })
}
function editWallht() {
    $('.wall-ht-conf').show();
}
function setWallht() {
    let height = 0
    var wall = [];
    var wall_list = B3D.model.floorplan.getWalls();
    let is_validHt = true
    // var selectedWallVal=JSON.parse($('#change-wall').val());
    wall_list.forEach(item => {
        //        console.log("wall list", item)
        if (item.id == wallID)
            wall = item
    })


    if (APP_UOM == 'IN')
        height = inToCm($('.wall-ht-conf input')[0].value)
    else if (APP_UOM == 'FT')
        height = Math.round(($('.wall-ht-conf input')[0].value * 30.48) + inToCm($('.wall-ht-conf input')[1].value))
    wall.onItems.forEach(w_items => {
        if (w_items.metadata.group == "Wall Cabinets") {
            if (height < (w_items.position.y + w_items.halfSize.y))
                is_validHt = false
        }
    })
    if (is_validHt) {
        wall.height = height
        wall.fireRedraw();
        B3D.model.floorplan.update();
        window.getMeasureInfo(B3D).then(_=>{
            buildAvailSpaceVar()
        })
    }
    else {
        alert("Modified height is lesser than the position of added items in the wall. Please remove it and try again...")
    }
}
function closeWallHtConf() {
    $('.wall-ht-conf').hide();
}

function buildWallInfoHTML(item) {
    //    console.log("build wall item", item)
    let wall_info = JSON.parse(item);
    //    console.log('wall_info',wall_info)
    let unit = ''
    let avail_space = { w: '', b: '' }
    let ht_in_IN = 0;
    let ht_in_FT = 0;
    // html = '<hr>'

    if (APP_UOM == 'IN') {
        ht_in_IN = window.cmToIn(wall_info.top)
        wall_info.total_space = window.cmToIn(wall_info.total_space).toFixed(0);
        wall_info.wall.w_cab = window.cmToIn(wall_info.wall.w_cab).toFixed(0);
        avail_space.w = wall_info.total_space - wall_info.wall.w_cab;
        wall_info.base.b_cab = window.cmToIn(wall_info.base.b_cab).toFixed(0);
        avail_space.b = wall_info.total_space - wall_info.base.b_cab;
        unit = ' In'
    } else if (APP_UOM == 'FT') {
        let ht = (window.cmToFtIN(wall_info.top)).split("'")
        ht_in_FT = ht[0]
        ht_in_IN = ht[1]
        avail_space.w = window.cmToFtIN(wall_info.total_space - wall_info.wall.w_cab);
        avail_space.b = window.cmToFtIN(wall_info.total_space - wall_info.base.b_cab);
        wall_info.total_space = window.cmToFtIN(wall_info.total_space);
        wall_info.wall.w_cab = window.cmToFtIN(wall_info.wall.w_cab);
        wall_info.base.b_cab = window.cmToFtIN(wall_info.base.b_cab);
    }

    let wall_measure = $('#wall-measure');

    let html = '';
    if (APP_UOM == 'IN')
        html = '<div style="display:none" class="wall-ht-conf"><label>Wall Height</label> <input maxlength="3" value="' + Math.round(ht_in_IN) + '"> In <button class="save" onClick="setWallht()"><span class="glyphicon glyphicon-ok"></span></button><button onClick="closeWallHtConf()"><span class="glyphicon glyphicon-remove"></span></button></div>';
    else if (APP_UOM == 'FT')
        html = '<div style="display:none" class="wall-ht-conf"><label>Wall Height</label> <input maxlength="2" value="' + Math.round(ht_in_FT) + '"> Ft <input maxlength="2" value="' + ht_in_IN + '">In <button  class="save" onClick="setWallht()"><span class="glyphicon glyphicon-ok"></span></button><button onClick="closeWallHtConf()"><span class="glyphicon glyphicon-remove"></span></button></div>';

    html += '<ul>'
    if (wall_info.top > 100)
        html += '<li class="title">Wall</li><li>Total: ' + wall_info.total_space + '' + unit + ' | Available: ' + avail_space.w + '' + unit + '<span  class="glyphicon glyphicon-edit  edit-con-wall" onClick=editConfiguration("Wall_Cabinets")></span></li>'
    html += '<li class="title">Base</li><li>Total: ' + wall_info.total_space + '' + unit + ' | Available: ' + avail_space.b + '' + unit + ' <span  class="glyphicon glyphicon-edit edit-con-wall" onClick=editConfiguration("Base_Cabinets")></span></li>'
    html += '</ul>';
    // console.log('hrml***',html)
    wall_measure.html(html)
}

var configWall = ''
function editConfiguration(type) {
    console.log('type', type)
    let metadata = { colors: {}, doors: { idx: 0 }, material: {}, drawer: {}, handle_fin: {}, handle_col: {}, corbels: {}, moldings: {}, valances: {}, floating_shelf: {}, misc_item: {}, hinges: {}, drawer_slides: {} }
    if (Object.keys(set_dr_flag[type.replace('_', " ")]).length)
        metadata = JSON.parse(JSON.stringify(set_dr_flag[type.replace('_', " ")]))

    configWall = type.replace('_', " ")
    console.log('metadata', metadata)
    selected_doorConfig = metadata;
    $('#saveConfig').hide()
    $('#set-all-config').show()
    $('#set-all-config span').text('Set for all ' + type.replace('_', ' '));
    createDropdown().then(_ => {
        let group_label = type.split('_');
        $("#door-config-modal #group_title").text(group_label[0] + " Style Configuration");
    })


}
function inRange(x, min, max) {
    let res=false;
    if(min<=x && max>=x)
        res=true;
    return res;
     
    // return ((x - min) * (x - max) <= 0);
}
function setWallSize() {
    let sel = $('#wall-ht option:selected').text();
    if (sel == 'Custom') {
        setWallSizeVal();
        $('#cust-wall-inp').show();
    } else if (sel == 'Half') {
        $('#cust-wall-inp').hide();
        $('#wall-ht option:selected').val('90')
    } else if (sel == 'Full') {
        $('#cust-wall-inp').hide();
        $('#wall-ht option:selected').val('244')
    }
}
function setWallSizeVal() {
    if ($('#wall-ht option:selected').text() == 'Custom') {
        let in_cm = (APP_UOM == 'IN' ? window.inToCm($("#cust-wall-inp").val()) : window.ftToCm($("#cust-wall-inp").val()));

        $('#wall-ht option:selected').val(in_cm)
    }
}

function checkWallAvailSpace(sum_items){
    return new Promise(resolve=>{
        let inRange={res:[]}
        if(sum_items.length){
            let selectedwall = JSON.parse($('#change-wall').val())
            if(selectedwall.hasOwnProperty('interiorStart')){
                let incType = 'z';  
                selectedwall.interiorStart['z'] = selectedwall.interiorStart.y;
                selectedwall.interiorEnd['z'] = selectedwall.interiorEnd.y;
                if (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) + 10 > selectedwall.total_space && (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) - 10 < selectedwall.total_space))
                    incType = 'x';
                let min;
                let max;
                let boundBox;
                if(incType=='x'){
                    min ={x:selectedwall.interiorStart.x,y:0,z:selectedwall.interiorStart.z}
                    max ={x:selectedwall.interiorEnd.x,y:selectedwall.top,z:(min.z+12)}
                    if ((selectedwall.interiorEnd.x - selectedwall.interiorStart.x) < 0){

                        min ={x:selectedwall.interiorEnd.x,y:0,z:selectedwall.interiorStart.y-12}
                    max ={x:selectedwall.interiorStart.x,y:selectedwall.top,z:selectedwall.interiorStart.y}
                    }
                }
                else{
                    min ={x:selectedwall.interiorStart.x-12,y:0,z:selectedwall.interiorStart.y};
                    max ={x:selectedwall.interiorStart.x,y:selectedwall.top,z:selectedwall.interiorEnd.y};
                    if ((selectedwall.interiorEnd.y - selectedwall.interiorStart.y) < 0) {
                         min ={x:selectedwall.interiorStart.x,y:0,z:selectedwall.interiorEnd.y};
                        max ={x:min.x+12,y:selectedwall.top,z:selectedwall.interiorStart.y};
                    }
                }
                boundBox=new THREE.Box3(min,max);
                B3D.model.checkforWallSpace(boundBox).then(res=>{
                    resolve({res:res,type:incType});
                 })
             
            }
          
        }
        else
        resolve (inRange)
        
    })
}
function buildAvailSpace(ht) {

return new Promise(resolve=>{
    let active_item = [];
    let occ_space = { wall: { w_cab: 0, w_item_no: 0 }, base: { b_cab: 0, b_item_no: 0 } };
    var sum_items = B3D.model.scene.getItems();
  
   checkWallAvailSpace(sum_items).then(inRange=>{
    // console.log('inRange',inRange)
    if(inRange.res.length)
        {   
            inRange.res.forEach(mer => {
                // console.log('merIn',mer)
                   let posY_top = mer.position.y + (mer.halfSize.y);
                   let posY_btm = mer.position.y - (mer.halfSize.y);
                //    let ht_top = (36 * 2.54);
                let rotation=Math.abs(mer.rotation.y);
                
                let ht_top = ht/2
                   let ht_btm = 34 * 2.54;
                //    console.log('top',posY_btm, 0, ht_btm)
                   let val_top = window.inRange(posY_top,  ht_top, ht);
                   let val_btm = window.inRange(posY_btm, 0, ht_btm);
                   if (val_top) {
                    // console.log('rotation',rotation,Math.PI/2)
                        if(rotation>0 && Math.PI/2>=rotation){
                            if(inRange.type=='x')
                             occ_space.wall.w_cab += 2*(mer.halfSize.z)
                            else
                                occ_space.wall.w_cab += 2*(mer.halfSize.x);
                        }
                           
                        else{
                            if(inRange.type=='x')
                            occ_space.wall.w_cab += 2*(mer.halfSize.x);
                            else
                            occ_space.wall.w_cab += 2*(mer.halfSize.z)
                        }
                            
                       occ_space.wall.w_item_no += 1;
                   }
                  if (val_btm) {
                    if(rotation>0 && Math.PI/2>=rotation)
                        {
                            if(inRange.type=='x')
                             occ_space.base.b_cab += 2*(mer.halfSize.z)
                            else
                             occ_space.base.b_cab += 2*(mer.halfSize.x)
                        }
                        
                    else{
                        if(inRange.type=='x')
                            occ_space.base.b_cab += 2*(mer.halfSize.x)
                        else
                        occ_space.base.b_cab += 2*(mer.halfSize.z)


                    }
                      


                       occ_space.base.b_item_no += 1;
                   }
           })
           resolve (occ_space);
       }
       else
       resolve (occ_space);
   })
})
    

   
        
    // },100)
   


    // console.log('result',result)
    
}
function validateMeasureEntry(type) {
    let min_max = {}
    let key = 'W'
    if (type.indexOf('height') != -1)
        key = 'H'
    else if (type.indexOf('depth') != -1)
        key = 'D'

    min_max['min'] = cmToIn(selectedItem.metadata.measurements['min' + key]);
    min_max['max'] = cmToIn(selectedItem.metadata.measurements['max' + key]);
    min_max['inc_dec'] = Number(selectedItem.metadata.measurements['inc_dec_' + key])
    return min_max;
}
var qty = function(INCH, FT, type) {
	return new Promise(resolve => {
		let class_name = '.quantity__plus';
		if (type == '-')
			class_name = '.quantity__minus';
		$(class_name).prop('disabled', false)

		let inch = $(INCH);
		let ft = $(FT);
		var valMeas = validateMeasureEntry(INCH);

		var value = Number(fracPlaintoDec(inch.val()));
		console.log('val',inch.val())
		let measType = 'width'
		if (INCH.indexOf('height') != -1)
			measType = 'height';
		else if ((INCH.indexOf('depth') != -1))
			measType = 'depth';

		// console.log('valMeas', INCH, FT,valMeas,type)
		if (APP_UOM == 'IN' && valMeas.inc_dec!=0) {
			if (type == '-') {
				value -= Number(valMeas.inc_dec);
                console.log('value',value,Number(valMeas.min))
				if (Number(valMeas.min) > Number(value)) {
                    if(Number.isInteger(value))
                        measureAlert('' + valMeas.min + ' In is the least measurement value for the selected Item')
					value = valMeas.min;
                   
                   
				}
			} else if (type == '+') {
				value += valMeas.inc_dec;
				if (Number(valMeas.max) < Number(value)) {
                    if(Number.isInteger(value))
                        measureAlert('' + valMeas.max + ' In is the most measurement value for the selected Item')
					value = valMeas.max;
                  
				}
			}
			setTimeout(function () {
				validateIncsizePos(measType, window.inToCm(value)).then(data => {
					if (data) {
						let frac = decToFracPlain(value);
                        if(selectedItem.metadata.measurements.hasOwnProperty('isSame_size'))
                            {
                                if(selectedItem.metadata.measurements.isSame_size=="true" &&  (measType=='depth' || measType=='width') ){
                                   $('#item-depth').val(frac);
                                   $('#item-width').val(frac);
                                }
                                    
                            }
						inch.val(frac);
						window.resize();
						$(class_name).prop('disabled', false);
						resolve(true);
					} else {
						measureAlert('There is either no space to left/rigth to the item or try by moving near by items.');
						$(class_name).prop('disabled', false);
						resolve(false);
					}
				})
			}, 100)
		} else {
			alert(measType.toUpperCase() + ' is standard size for the selected item and cannot be resized.');
			resolve(false);
		}
    // else if (APP_UOM == 'FT') {
    //     let realWIn = value;
    //     realWft = Number(ft.val()) + Math.floor(value / 12);
    //     if (type == '-') {
    //         value -= valMeas.inc_dec;
    //         let fttoCm = (ft.val() * 12) + value
    //         if (Number(valMeas.min) > Number(fttoCm)) {
    //             // value+=valMeas.inc_dec
    //             measureAlert('' + ft.val() + ' Ft ' + inch.val() + ' In is the least measurement value for the selected Item')
    //             $(class_name).prop('disabled', false)
    //         }
    //         else {
    //             // setTimeout(function(){
    //             validateIncsizePos(measType, window.inToCm(fttoCm)).then(data => {
    //                 if (data) {
    //                     if (value < 0) {
    //                         realWIn = value + 12;
    //                         inch.val(realWIn);
    //                         ft.val(realWft)
    //                     }
    //                     else if (value > 12) {
    //                         realWIn = value - 12;
    //                         inch.val(realWIn);
    //                         ft.val(realWft);
    //                     }
    //                     else { inch.val(value); }
    //                     window.resize()

    //                 }
    //                 else
    //                     measureAlert('There is either no space to left/rigth to the item or try by moving near by items.')
    //             })
    //             // },250)
    //             $(class_name).prop('disabled', false)
    //         }
    //     }
    //     if (type == '+') {
    //         value += valMeas.inc_dec;
    //         let fttoCm = (ft.val() * 12) + value
    //         console.log('val', fttoCm)
    //         if (Number(valMeas.max) < Number(fttoCm)) {
    //             //  value+=valMeas.inc_dec
    //             measureAlert('' + ft.val() + ' Ft ' + inch.val() + ' In is the most measurement value for the selected Item')
    //             $(class_name).prop('disabled', false)
    //         }
    //         else {
    //             setTimeout(function () {
    //                 validateIncsizePos(measType, window.inToCm(fttoCm)).then(data => {
    //                     if (data) {
    //                         if (value < 0) {
    //                             realWIn = value + 12;
    //                             inch.val(realWIn);
    //                             ft.val(realWft)
    //                         }
    //                         else if (value > 12) {
    //                             realWIn = value - 12;
    //                             inch.val(realWIn);
    //                             ft.val(realWft);

    //                         }
    //                         else { inch.val(value); }
    //                         window.resize()
    //                     }
    //                     else
    //                         measureAlert('There is either no space to left/rigth to the item or try by moving near by items.')
    //                 })
    //             }, 250)
    //             $(class_name).prop('disabled', false)
    //         }
    //     }
    // }

	})

}

function validateIncsizePos(type, val) {
    console.log('val12', val)
    return new Promise(resolve => {
        let minR, minL;
        let maxR, maxL;
        let boxR, boxL;

        let boxArry = [];
        let selectedwall = JSON.parse($('#change-wall').val())
        selectedwall.interiorEnd['z'] = selectedwall.interiorEnd.y;
        selectedwall.interiorStart['z'] = selectedwall.interiorStart.y;
        let incType = 'z'
        if (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) + 10 > selectedwall.total_space && (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) - 10 < selectedwall.total_space))
            incType = 'x'
        console.log('incType', incType)
        if (incType == 'x') {
            minR = new THREE.Vector3((selectedItem.position.x  - selectedItem.halfSize.x), selectedItem.position.y - selectedItem.halfSize.y, selectedItem.position.z - selectedItem.halfSize.z)
            maxR = new THREE.Vector3(minR.x + (type == 'width' ? val : (2 * selectedItem.halfSize.x)), minR.y + (type == 'height' ? val : (2 * selectedItem.halfSize.y)), minR.z + (type == 'depth' ? val : (2 * selectedItem.halfSize.z)))
            boxR = new THREE.Box3(minR, maxR)
            boxArry.push(boxR)
            maxL = new THREE.Vector3((selectedItem.position.x + selectedItem.halfSize.x), selectedItem.position.y + selectedItem.halfSize.y, selectedItem.position.z + selectedItem.halfSize.z)
            minL = new THREE.Vector3(maxL.x - (type == 'width' ? val : (2 * selectedItem.halfSize.x)), maxL.y - (type == 'height' ? val : (2 * selectedItem.halfSize.y)), maxL.z - (type == 'depth' ? val : (2 * selectedItem.halfSize.z)))
            boxL = new THREE.Box3(minL, maxL)
            boxArry.push(boxL)
            if ((selectedwall.interiorEnd.x - selectedwall.interiorStart.x) < 0) {
                boxArry = []
                boxR = new THREE.Box3(minR, maxR)
                boxL = new THREE.Box3(minL, maxL)
                boxArry.push(boxL);
                boxArry.push(boxR)
            }
        }
        if (incType == 'z') {
            console.log('boxArry***', boxArry)

            minR = new THREE.Vector3((selectedItem.position.x - selectedItem.halfSize.z), selectedItem.position.y - selectedItem.halfSize.y, selectedItem.position.z  - selectedItem.halfSize.x)
            maxR = new THREE.Vector3(minR.x + (type == 'depth' ? val : (2 * selectedItem.halfSize.z)), minR.y + (type == 'height' ? val : (2 * selectedItem.halfSize.y)), minR.z + ((type == 'width' ? val : (2 * selectedItem.halfSize.x))))
            boxR = new THREE.Box3(minR, maxR)
            boxArry.push(boxR)
            maxL = new THREE.Vector3(Math.round(selectedItem.position.x - selectedItem.halfSize.z), selectedItem.position.y - selectedItem.halfSize.y, ((selectedItem.position.z) + selectedItem.halfSize.x) - ((type == 'width' ? val : (2 * selectedItem.halfSize.x))))
            minL = new THREE.Vector3(Math.round(maxL.x + (type == 'depth' ? val : (2 * selectedItem.halfSize.z))), maxL.y + (type == 'height' ? val : (2 * selectedItem.halfSize.y)), ((selectedItem.position.z ) + selectedItem.halfSize.x))
            boxL = new THREE.Box3(maxL, minL)
            boxArry.push(boxL)
            if ((selectedwall.interiorEnd.y - selectedwall.interiorStart.y) < 0) {
                boxArry = []
                boxR = new THREE.Box3(minR, maxR)
                boxL = new THREE.Box3(maxL, minL)
                boxArry.push(boxL);
                boxArry.push(boxR);
            }

            console.log('boxArry', boxArry)
        }
        B3D.model.checkforvalidPos(boxArry[0], selectedItem.material.uuid).then(data => {
            console.log('box', data)
            if (incType == 'x') {
                if ((selectedwall.interiorEnd.x - selectedwall.interiorStart.x) > 0) {
                    if (!data.isInter && data.boundBox.max[incType] < selectedwall.interiorEnd[incType] && data.boundBox.max[incType] > selectedwall.interiorStart[incType]) {
                        selectedItem.position.set(data.pos.x , data.pos.y, data.pos.z)
                        resolve(true)
                    }
                    else {
                        B3D.model.checkforvalidPos(boxArry[1], selectedItem.material.uuid).then(data1 => {
                            if (!data1.isInter && data1.boundBox.min[incType] < selectedwall.interiorEnd[incType] && data1.boundBox.min[incType] > selectedwall.interiorStart[incType]) {
                                selectedItem.position.set(data1.pos.x , data1.pos.y, data1.pos.z)
                                resolve(true)
                            }
                            else
                                resolve(false)
                        })
                    }
                }
                else {

                    console.log('else', selectedwall, data)
                    if (data.isInter == false && data.boundBox.min[incType] > selectedwall.interiorEnd[incType] && data.boundBox.min[incType] < selectedwall.interiorStart[incType]) {
                        console.log('elseif')
                        selectedItem.position.set(data.pos.x , data.pos.y, data.pos.z)
                        resolve(true)
                    }
                    else {
                        B3D.model.checkforvalidPos(boxArry[1], selectedItem.material.uuid).then(data1 => {
                            console.log('gree112', data1, selectedwall.interiorStart[incType], data1.boundBox.max[incType], selectedwall.interiorEnd[incType])
                            if (data1.isInter == false && data1.boundBox.max[incType] > selectedwall.interiorEnd[incType] && data1.boundBox.max[incType] < selectedwall.interiorStart[incType]) {
                                selectedItem.position.set(data1.pos.x , data1.pos.y, data1.pos.z)
                                resolve(true)
                            }
                            else
                                resolve(false)
                        })
                    }

                }
            }
            else if (incType == 'z') {
                if ((selectedwall.interiorEnd.y - selectedwall.interiorStart.y) > 0) {
                    console.log('gree', selectedwall.interiorStart[incType], data.boundBox.min[incType], selectedwall.interiorEnd[incType])
                    if (data.isInter == false && data.boundBox.max[incType] < selectedwall.interiorEnd[incType] && data.boundBox.max[incType] > selectedwall.interiorStart[incType]) {
                        selectedItem.position.set(data.pos.x, data.pos.y, data.pos.z )
                        resolve(true)
                    }
                    else {
                        B3D.model.checkforvalidPos(boxArry[1], selectedItem.material.uuid).then(data1 => {
                            console.log('gree11', data1, selectedwall.interiorStart[incType], data1.boundBox.max[incType], selectedwall.interiorEnd[incType])
                            if (data1.isInter == false && data1.boundBox.min[incType] < selectedwall.interiorEnd[incType] && data1.boundBox.min[incType] > selectedwall.interiorStart[incType]) {
                                selectedItem.position.set(data1.pos.x, data1.pos.y, data1.pos.z )
                                resolve(true)
                            }
                            else
                                resolve(false)
                        })
                    }
                }
                else {
                    console.log('else', selectedwall, data)
                    if (data.isInter == false && data.boundBox.min[incType] > selectedwall.interiorEnd[incType] && data.boundBox.min[incType] < selectedwall.interiorStart[incType]) {
                        console.log('elseif')
                        selectedItem.position.set(data.pos.x, data.pos.y, data.pos.z )
                        resolve(true)
                    }
                    else {
                        // resolve(false)
                        B3D.model.checkforvalidPos(boxArry[1], selectedItem.material.uuid).then(data1 => {
                            console.log('gree112', data1, selectedwall.interiorStart[incType], data1.boundBox.max[incType], selectedwall.interiorEnd[incType])
                            if (data1.isInter == false && data1.boundBox.max[incType] > selectedwall.interiorEnd[incType] && data1.boundBox.max[incType] < selectedwall.interiorStart[incType]) {
                                selectedItem.position.set(data1.pos.x, data1.pos.y, data1.pos.z )
                                resolve(true)
                            }
                            else
                                resolve(false)
                        })
                    }
                }
            }


        })

    })


}
function measureAlert(msg) {
    alert(msg);
}
function resize() {
    if (APP_UOM == 'IN') {
        
        selectedItem.resize(
            window.inToCm(fracPlaintoDec($("#item-height").val())),
            window.inToCm(fracPlaintoDec($("#item-width").val())),
            window.inToCm(fracPlaintoDec($("#item-depth").val())));
    }
    else if (APP_UOM == 'FT') {
        selectedItem.resize(
            window.ftToCm($("#item-height-ft").val()) + window.inToCm($("#item-height-in").val()),
            window.ftToCm($("#item-width-ft").val()) + window.inToCm($("#item-width-in").val()),
            window.ftToCm($("#item-depth-ft").val()) + window.inToCm($("#item-depth-in").val()));
    }
    window.getMeasureInfo(B3D).then(_=>{
        buildAvailSpaceVar()
    })
}

function closeAllPopups() {
    $(".menu ul").hide();
    $("#wall_options").hide();
}



colorProp = {}
jsonstackref = []
function detectchanges(item, type) {
    // console.log('item', item)
    jsonstack.push(item);
    // sessionStorage.setItem('changes', JSON.stringify([item]))
    if (!colorProp[item.uuid] )
        colorProp[item.uuid] = [];
    colorProp[item.uuid].push({ index: jsonstack.length - 1, cur_name: item.textureName, prev_name: item.prev_textureName, cur_url: item.textureUrl, prev_url: item.prev_textureUrl, type: type, meshProp: item });
console.log('jsonstack',jsonstack,colorProp)
}

function undoAction() {
    if (jsonstack.length > 0 && jsonstack.length >= jsonstackindex) {
        jsonstackindex = jsonstackindex == 0 ? jsonstack.length - 1 : jsonstackindex - 1;
        Object.keys(colorProp).forEach((key, value) => {
            if (jsonstack[jsonstackindex] && (key + '') == jsonstack[jsonstackindex].uuid) {
                let prev = colorProp[key].find(res => res.index == jsonstackindex);
                if (prev.type == 'copy' || prev.type == 'add') {
                    console.log('B#D', B3D,)
                    // B3D.model.roomSavedCallbacks.remove()
                    jsonstack[jsonstackindex].remove();
                    $("#cart_count").text(Number($("#cart_count").text()) - 1);
                }
                else if (prev.type == 'del') {
                    let textureInfo = {
                        textureUrl: prev.prev_url,
                        textureName: prev.prev_name,
                        uuid: jsonstack[jsonstackindex].uuid,
                        stackIndex: jsonstackindex,
                        meshprop: prev.meshProp
                    }

                    B3D.model.scene.addItem(jsonstack[jsonstackindex].metadata.itemType, jsonstack[jsonstackindex].metadata.modelUrl, jsonstack[jsonstackindex].metadata, textureInfo, jsonstack[jsonstackindex].position).then(_=>{
                        $("#cart_count").text(Number($("#cart_count").text()) + 1);
                    })

                }
                else if (prev.type == 'color' || prev.type == 'apply-all') {
                    jsonstack[jsonstackindex].updateTexture(jsonstack[jsonstackindex], prev.prev_url, prev.prev_name);
                    if (prev.type == 'apply-all') {
                        Object.keys(colorProp).forEach((key, value) => {
                            if (key + '' == jsonstack[jsonstackindex - 1].uuid) {
                                let prev = colorProp[key].find(res => res.index == jsonstackindex - 1);
                                if (prev.type == 'apply-all')
                                    undoAction()
                            }
                        })
                    }
                }
            }
        })
        setButtonState()
        B3D.model.floorplan.update();
        // console.log("after undo", jsonstackindex, jsonstack[jsonstackindex]);

    }
}
function setButtonState() {
    if (jsonstackindex < jsonstack.length)
        $('.redo').prop('disabled', false);
    else
        $('.redo').prop('disabled', true);
    if (jsonstackindex == 0)
        $('.undo').prop('disabled', true);
    else
        $('.undo').prop('disabled', false);
}
function redoAction() {
    let res_type = ''
    if (jsonstack.length > 0 && jsonstack.length >= jsonstackindex) {
        jsonstackindex = jsonstackindex == 0 ? 0 : jsonstackindex;
        Object.keys(colorProp).forEach((key, value) => {
            if (jsonstack[jsonstackindex] && key + '' == jsonstack[jsonstackindex].uuid) {
                let prev = colorProp[key].find(res => res.index == jsonstackindex);
                let textureInfo = {
                    textureUrl: prev.cur_url,
                    textureName: prev.cur_name,
                    uuid: jsonstack[jsonstackindex].uuid,
                    stackIndex: jsonstackindex,
                    meshprop: prev.meshProp
                }
                if (prev.type == 'add' || prev.type == 'copy') {
                    B3D.model.scene.addItem(jsonstack[jsonstackindex].metadata.itemType, jsonstack[jsonstackindex].metadata.modelUrl, jsonstack[jsonstackindex].metadata, textureInfo, jsonstack[jsonstackindex].position).then(_=>{
                        $("#cart_count").text(Number($("#cart_count").text()) + 1)

                    })
                }
                else if (prev.type == 'del') {
                    jsonstack[jsonstackindex].remove();
                    $("#cart_count").text(Number($("#cart_count").text()) - 1)
                }
                else if (prev.type == 'color' || prev.type == 'apply-all') {
                    jsonstack[jsonstackindex].updateTexture(jsonstack[jsonstackindex], prev.cur_url, prev.cur_name);
                    res_type = prev.type
                    if (prev.type == 'apply-all') {
                        if (jsonstack.length > 0 && jsonstackindex < jsonstack.length - 1 && jsonstack[jsonstackindex + 1].uuid != '') {
                            console.log(' jsonstackindex3', jsonstackindex, jsonstack[jsonstackindex + 1].uuid, jsonstack.length)
                            Object.keys(colorProp).forEach((key, value) => {
                                if (jsonstack[jsonstackindex + 1] && key + '' == jsonstack[jsonstackindex + 1].uuid) {
                                    let prev = colorProp[key].find(res => res.index == jsonstackindex + 1);
                                    res_type = prev.type
                                    if (prev.type == 'apply-all') {
                                        jsonstackindex = prev.index;
                                        redoAction()
                                    }
                                }
                            })
                        }
                    }
                }
            }
        })

        if (jsonstack.length > jsonstackindex)
            jsonstackindex += 1
        else
            jsonstackindex = jsonstack.length - 1
        setButtonState();
        B3D.model.floorplan.update();
    }
}
function captureImage() {
    return new Promise(function (resolve, reject) {
        var element = $("#viewer")[0];

        html2canvas(element, {
            onrendered: function (canvas) {
                var imageData = canvas.toDataURL("image/jpg");
                resolve(imageData);
            }
        });
    });
}
function saveDesigns(type) {
    var jsonData = B3D.model.exportSerialized();
    captureImage().then(function (imageData) {
        $.ajax({
            type: "POST",
            async: false,
            url: api_url+'save_design.php',
            data: { jsondata: JSON.stringify(jsonData), imagedata: imageData, type: type },
            success: function () {
                if (type == 'mydesign')
                    alert("Your design saved Successfully and available at my designs section!");

            },
            failure: function () {
                alert("Error saving your design. Please try again later!");
            }
        });
    });
}



$(document).ready(function () {
	$("#item-width").click((ele) => {
		if(Number(selectedItem.metadata.measurements.inc_dec_W))
			numpad.show(ele);
		else
			alert('Width is standard size for the selected item. Cannot be resized.');
	});
	$("#item-height").click((ele) => {
        if(Number(selectedItem.metadata.measurements.inc_dec_H))
            numpad.show(ele);
        else
			alert('Height is standard size for the selected item. Cannot be resized.');
	});
	$("#item-depth").click((ele) => {
        if(Number(selectedItem.metadata.measurements.inc_dec_D))
            numpad.show(ele);
        else
			alert('Depth is standard size for the selected item. Cannot be resized.');
	});

    $('#fetching-container').hide();
    $("#loading-container").show();
    $('.redo').prop('disabled', true);
    $('.undo').prop('disabled', true);
    var selected_style = ""; // getCookie("selected_3d_style");
    //	var selected_style = "batwing";
    $("#goto_wall_planner").click(function () {
        closeAllPopups();
        $("#floorplan_tab").trigger("click");
        $(".nav-sidebar").hide();
        $(".nav-sidebar").next("hr").hide();
        $("#wall-details").hide();
        $("#sidebar").css('width', '12.5%');

        $("#uom_container").css("margin", "15px 40% 0 40%");
    });
    $('#edit-sel-config').on('click', function () {
        let metadata = JSON.parse(JSON.stringify(selectedItem.metadata.door_config))
        console.log('metadata', metadata)
        glob_matadata =selectedItem.metadata;
        $('#config-others').show()
        
        selected_doorConfig = metadata;
        $('#saveConfig').hide()
        $('#set-all-config').show()
        let idx = { fin: 0, col: 0 }
        if ((selected_doorConfig.handle_fin).hasOwnProperty('idx') && selected_doorConfig.handle_fin.idx + 1) {
            idx.fin = metadata.handle_fin.idx + 1;
            idx.col = metadata.handle_col.idx + 1;
        }
        $('#set-all-config span').text('Set for all ' + selectedItem.metadata.group)
        if(glob_matadata.group=='Add Ons'){
            $('#set-all-config span').text('Set for all ' + selectedItem.metadata.itemName)
            $('#config-others').hide();
        }
            
        createDropdown().then(_ => {
            // createDDforHandFinish(idx);
        })
    })
    $(".undo").on("click", function () {
        undoAction();
    });

    $(".redo").on("click", function () {
        redoAction();
    });
    $('#saveJSONFile').click(function () {
        saveDesigns('mydesign')
    });
    window.addEventListener("beforeunload", function (event) {
        event.returnValue = "";

        let cart_count = Number($("#cart_count").text());
        if (cart_count) {
            $('#confirmation-modal').show();
        }

        return "hello";
    });
    window.onbeforeunload = function (e) {
        saveDesigns('draft')
        return "Do you really want to close?";
    };
    setInterval(function () {
        saveDesigns('auto-save');
    }, 90000)
    $("#back-to-viewer").click(function () {
        $("#uom_container").show();
        $("#update-floorplan").trigger("click");
    })

    $("#back-to-wall, #prev_summary_handler").click(function () {
        $("#update-floorplan").trigger("click");
        $("#goto_wall_planner").show();
        $("#prev_summary_handler").hide();
        $("#uom_container").show();
        $("#prev_tab").show();
        $('#prev_summary').hide();
        $("#sidebar").show();
        $("#uom_container").removeClass('uom-left').addClass('uom-center');
    })

    $('#prev_tab').click(function () {
        $(".nav-sidebar").hide();
        $(".nav-sidebar").next("hr").hide();
        $("#wall-details").hide();
        $("#sidebar").hide();
        $("#uom_container").hide();
        $("#prev_summary_handler").show();

        $("#prev_summary").show();
        $("#prev_tab").hide();
        $('#fetching-container').show();
        sum_type = 'prev_summary';

        $("#uom_container").removeClass('uom-center').addClass('uom-left');

        let cart_count = Number($("#cart_count").text());
        if (cart_count) {
            $("#prev_summary button#view_summary").show();
            setTimeout(_ => {
                window.prepareSummmaryHTML(blueprint3d, APP_UOM).then(grouped_items => {

                    console.log('getPrice', grouped_items)
                    var html = grpdQty(grouped_items);
                    if (sum_type == 'summary-viewer')
                        loadPriceSummary();

                    $('#' + sum_type + ' #content').html(html);

                    $('#fetching-container').hide();

                });
            }, 100)

        } else {
            $("#prev_summary button#view_summary").hide();
            let html = '<div class="empty-cart">Cart is empty!</div>';
            $('#' + sum_type + ' #content').html(html);
            $('#fetching-container').hide();
        }
        captureImage().then(data => {
            window.sessionStorage.setItem('cartdesign', data);
        })
    })
    /*
        $("#goback").click(function () {
            window.location.href = "configure.html";
        });
    */
    // main setup
    var opts = {
        floorplannerElement: 'floorplanner-canvas',
        threeElement: '#viewer',
        threeCanvasElement: 'three-canvas',
        textureDir: "images/models/textures/",
        widget: false
    }
    var blueprint3d = new BP3D.Blueprint3d(opts);
    B3D = blueprint3d;
    blueprint3d.three.stopSpin();

    var modalEffects = new ModalEffects(blueprint3d);
    var viewerFloorplanner = new ViewerFloorplanner(blueprint3d);
    var contextMenu = new ContextMenu(blueprint3d);

    if (selected_style.trim() != "")
        selected_style = selected_style;
    else
        selected_style = "";
    window.loadPreStyle(selected_style, blueprint3d);

    $("#preload-viewer li").click(function () {
        let selected_style = $(this)[0].outerText.toLowerCase();
        window.loadPreStyle(selected_style, blueprint3d);
        $("#uom_container").show();
    })
    $("#clear-room").click(function () {
        window.loadPreStyle("", blueprint3d);
    })


    $("#standard_items_tab").click(function () {
        $("#add-items").hide();
        $("#add-standard-items").show();
        $("#context-menu").hide();
    })
    $("#items_tab").click(function () {
        $("#add-standard-items").hide();
        $("#add-items").show();
        $("#context-menu").hide();
    })

    window.initMeasureOnclik(blueprint3d)

    window.getData("get_json_data", "items.json").then(builtin_data => {
        loadItemsList("add-standard-items", builtin_data);

        window.getData("get_json_data", "catalog-items.json").then(data => {
            $("#loading-container").hide();
            $("#loading-container").css("width", "75%");

            loadItemsList("add-items", data);

            var sideMenu = new SideMenu(blueprint3d, viewerFloorplanner, modalEffects);

            setTimeout(function () {
                $("#goto_wall_planner").trigger("click");
            }, 100)

            //	  var textureSelector = new TextureSelector(blueprint3d, sideMenu);
            var cameraButtons = new CameraButtons(blueprint3d);
            mainControls(blueprint3d);

            $(".items-wrapper h3").click(function () {
                let is_active = ($(this).parent().hasClass("active") ? 1 : 0);

                var parent_viewer = $(this).parent().parent()[0];
                $("#" + parent_viewer.id + " .items-wrapper").map(function () {
                    $(this).removeClass("active");
                })
                if (!is_active)
                    $(this).parent().addClass("active");
            })
        });
    });

    /*
        window.getData("getDraft_Des", '').then(data => {
            let draft_des = '';
            let load = 'load'
            if (data.length) {
                let def_path = "no-image.png"
                draft_des += "<div  class='modal-content'><img src='draft_designs/images/" + data[0].userid + ".png' onerror='this.onerror=null;this.src=" + def_path + "' /><br/><span>You have unsaved design for these username. Do you want retrive your changes?</span>"
                draft_des += '<div class="ret-btns"><button class="close-draft">No, Cancel</button><button class="draft-btn-type"  >Yes, Load</button>  </div></div>';
                $('#checkdesign-modal').html(draft_des);
                $('#checkdesign-modal').show()
                $('.draft-btn-type').click(function () {
                    window.loadMyDesign(data[0].image_path, "draft_designs/data/")
                    $('#checkdesign-modal').hide();
                })
                $('.close-draft').click(function () {
                    $('#checkdesign-modal').hide();
                    $('#confirmation-modal').hide()
    
                })
            }
        })
    */
    $(".menu").click(function () {
        let is_visible = $(this).children("ul").is(":visible");

        closeAllPopups();

        if (!is_visible)
            $(".menu ul").show();
    })

    $(".logout").click(function () {
        //		deleteCookie("user");
        window.location.href = "logout";
    })

    $(".admin").click(function () {
        //		deleteCookie("user");
        window.location.href = "admin";
    })

    $("#selected_mode").click(function () {
        closeAllPopups();
        $("#wall_options").toggle();
    })

    /*
        $(".dropdown-menu li a").click(function () {
            var selText = $(this).text();
            var imgSource = $(this).find('img').attr('src');
            console.log(imgSource,selText)
            var img = '<img src="' + imgSource + '"/>';        
            $(this).parents('.btn-group').find('.dropdown-toggle').html(img + ' <div >' + selText + ' <span style="margin-left:85px;" class="caret"></span></div>');
            selectedItem.updateTexture(selectedItem,imgSource,selText);
             B3D.model.floorplan.update();
        });
    */

});


async function getData(action, file_path, params) {
    return new Promise(resolve => {
        $.ajax({
            url: api_url+"ajax_process.php",
            type: "POST",
            dataType: "json",
            data: { "action": action, "file": file_path, "params": JSON.stringify(params) },
            async: false,
            success: function (data) {
                resolve(data);
            },
            error: handleAjaxError
        });
    })
}

function handleAjaxError(xhr, textStatus, errorThrown) {
    switch (xhr.status) {
        case 401: alert("It seems your session was expired, so we are redirecting you to login page.");
            window.location.href = root_path + "user/login.html";
            break;
        case 404: alert("Requested service resource was not found. Please contact the administrator ...!");
            break;
        default: alert("Something went wrong, your request could not be completed right now.\n\nPlease try again later...!");
            break;
    }
}
