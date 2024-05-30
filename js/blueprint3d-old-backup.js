var BP3D;
(function (BP3D) {
    var Core;
    (function (Core) {
        var Utils = (function () {
            function Utils() { }
            Utils.pointDistanceFromLine = function (x, y, x1, y1, x2, y2) {
                var tPoint = Utils.closestPointOnLine(x, y, x1, y1, x2, y2);
                var tDx = x - tPoint.x;
                var tDy = y - tPoint.y;
                return Math.sqrt(tDx * tDx + tDy * tDy);
            };
            Utils.closestPointOnLine = function (x, y, x1, y1, x2, y2) {
                var tA = x - x1;
                var tB = y - y1;
                var tC = x2 - x1;
                var tD = y2 - y1;
                var tDot = tA * tC + tB * tD;
                var tLenSq = tC * tC + tD * tD;
                var tParam = tDot / tLenSq;
                var tXx,
                    tYy;
                if (tParam < 0 || (x1 == x2 && y1 == y2)) {
                    tXx = x1;
                    tYy = y1;
                } else if (tParam > 1) {
                    tXx = x2;
                    tYy = y2;
                } else {
                    tXx = x1 + tParam * tC;
                    tYy = y1 + tParam * tD;
                }
                return {
                    x: tXx,
                    y: tYy
                };
            };
            Utils.distance = function (x1, y1, x2, y2) {
                return Math.sqrt(Math.pow(x2 - x1, 2) +
                    Math.pow(y2 - y1, 2));
            };
            Utils.angle = function (x1, y1, x2, y2) {
                var tDot = x1 * x2 + y1 * y2;
                var tDet = x1 * y2 - y1 * x2;
                var tAngle = -Math.atan2(tDet, tDot);
                return tAngle;
            };
            Utils.angle2pi = function (x1, y1, x2, y2) {
                var tTheta = Utils.angle(x1, y1, x2, y2);
                if (tTheta < 0) {
                    tTheta += 2 * Math.PI;
                }
                return tTheta;
            };
            Utils.isClockwise = function (points) {
                var tSubX = Math.min(0, Math.min.apply(null, Utils.map(points, function (p) {
                    return p.x;
                })));
                var tSubY = Math.min(0, Math.min.apply(null, Utils.map(points, function (p) {
                    return p.x;
                })));
                var tNewPoints = Utils.map(points, function (p) {
                    return {
                        x: p.x - tSubX,
                        y: p.y - tSubY
                    };
                });
                var tSum = 0;
                for (var tI = 0; tI < tNewPoints.length; tI++) {
                    var tC1 = tNewPoints[tI];
                    var tC2;
                    if (tI == tNewPoints.length - 1) {
                        tC2 = tNewPoints[0];
                    } else {
                        tC2 = tNewPoints[tI + 1];
                    }
                    tSum += (tC2.x - tC1.x) * (tC2.y + tC1.y);
                }
                return (tSum >= 0);
            };
            Utils.guid = function () {
                var tS4 = function () {
                    return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
                };
                return tS4() + tS4() + '-' + tS4() + '-' + tS4() + '-' +
                    tS4() + '-' + tS4() + tS4() + tS4();
            };
            Utils.polygonPolygonIntersect = function (firstCorners, secondCorners) {
                for (var tI = 0; tI < firstCorners.length; tI++) {
                    var tFirstCorner = firstCorners[tI],
                        tSecondCorner;
                    if (tI == firstCorners.length - 1) {
                        tSecondCorner = firstCorners[0];
                    } else {
                        tSecondCorner = firstCorners[tI + 1];
                    }
                    if (Utils.linePolygonIntersect(tFirstCorner.x, tFirstCorner.y, tSecondCorner.x, tSecondCorner.y, secondCorners)) {
                        return true;
                    }
                }
                return false;
            };
            Utils.linePolygonIntersect = function (x1, y1, x2, y2, corners) {
                for (var tI = 0; tI < corners.length; tI++) {
                    var tFirstCorner = corners[tI],
                        tSecondCorner;
                    if (tI == corners.length - 1) {
                        tSecondCorner = corners[0];
                    } else {
                        tSecondCorner = corners[tI + 1];
                    }
                    if (Utils.lineLineIntersect(x1, y1, x2, y2, tFirstCorner.x, tFirstCorner.y, tSecondCorner.x, tSecondCorner.y)) {
                        return true;
                    }
                }
                return false;
            };
            Utils.lineLineIntersect = function (x1, y1, x2, y2, x3, y3, x4, y4) {
                function tCCW(p1, p2, p3) {
                    var tA = p1.x,
                        tB = p1.y,
                        tC = p2.x,
                        tD = p2.y,
                        tE = p3.x,
                        tF = p3.y;
                    return (tF - tB) * (tC - tA) > (tD - tB) * (tE - tA);
                }
                var tP1 = {
                    x: x1,
                    y: y1
                },
                    tP2 = {
                        x: x2,
                        y: y2
                    },
                    tP3 = {
                        x: x3,
                        y: y3
                    },
                    tP4 = {
                        x: x4,
                        y: y4
                    };
                return (tCCW(tP1, tP3, tP4) != tCCW(tP2, tP3, tP4)) && (tCCW(tP1, tP2, tP3) != tCCW(tP1, tP2, tP4));
            };
            Utils.pointInPolygon = function (x, y, corners, startX, startY) {
                startX = startX || 0;
                startY = startY || 0;
                var tMinX = 0,
                    tMinY = 0;
                if (startX === undefined || startY === undefined) {
                    for (var tI = 0; tI < corners.length; tI++) {
                        tMinX = Math.min(tMinX, corners[tI].x);
                        tMinY = Math.min(tMinX, corners[tI].y);
                    }
                    startX = tMinX - 10;
                    startY = tMinY - 10;
                }
                var tIntersects = 0;
                for (var tI = 0; tI < corners.length; tI++) {
                    var tFirstCorner = corners[tI],
                        tSecondCorner;
                    if (tI == corners.length - 1) {
                        tSecondCorner = corners[0];
                    } else {
                        tSecondCorner = corners[tI + 1];
                    }
                    if (Utils.lineLineIntersect(startX, startY, x, y, tFirstCorner.x, tFirstCorner.y, tSecondCorner.x, tSecondCorner.y)) {
                        tIntersects++;
                    }
                }
                return ((tIntersects % 2) == 1);
            };
            Utils.polygonInsidePolygon = function (insideCorners, outsideCorners, startX, startY) {
                startX = startX || 0;
                startY = startY || 0;
                for (var tI = 0; tI < insideCorners.length; tI++) {
                    if (!Utils.pointInPolygon(insideCorners[tI].x, insideCorners[tI].y, outsideCorners, startX, startY)) {
                        return false;
                    }
                }
                return true;
            };
            Utils.polygonOutsidePolygon = function (insideCorners, outsideCorners, startX, startY) {
                startX = startX || 0;
                startY = startY || 0;
                for (var tI = 0; tI < insideCorners.length; tI++) {
                    if (Utils.pointInPolygon(insideCorners[tI].x, insideCorners[tI].y, outsideCorners, startX, startY)) {
                        return false;
                    }
                }
                return true;
            };
            Utils.forEach = function (array, action) {
                for (var tI = 0; tI < array.length; tI++) {
                    action(array[tI]);
                }
            };
            Utils.forEachIndexed = function (array, action) {
                for (var tI = 0; tI < array.length; tI++) {
                    action(tI, array[tI]);
                }
            };
            Utils.map = function (array, func) {
                var tResult = [];
                array.forEach(function (element) {
                    tResult.push(func(element));
                });
                return tResult;
            };
            Utils.removeIf = function (array, func) {
                var tResult = [];
                array.forEach(function (element) {
                    if (!func(element)) {
                        tResult.push(element);
                    }
                });
                return tResult;
            };
            Utils.cycle = function (arr, shift) {
                var tReturn = arr.slice(0);
                for (var tI = 0; tI < shift; tI++) {
                    var tmp = tReturn.shift();
                    tReturn.push(tmp);
                }
                return tReturn;
            };
            Utils.unique = function (arr, hashFunc) {
                var tResults = [];
                var tMap = {};
                for (var tI = 0; tI < arr.length; tI++) {
                    if (!tMap.hasOwnProperty(arr[tI])) {
                        tResults.push(arr[tI]);
                        tMap[hashFunc(arr[tI])] = true;
                    }
                }
                return tResults;
            };
            Utils.removeValue = function (array, value) {
                // console.log('fina',array, value)
                for (var tI = array.length - 1; tI >= 0; tI--) {
                    if (array[tI] === value) {
                        array.splice(tI, 1);
                    }
                }
            };
            Utils.subtract = function (array, subArray) {
                return Utils.removeIf(array, function (el) {
                    return Utils.hasValue(subArray, el);
                });
            };
            Utils.hasValue = function (array, value) {
                for (var tI = 0; tI < array.length; tI++) {
                    if (array[tI] === value) {
                        return true;
                    }
                }
                return false;
            };
            return Utils;
        })();
        Core.Utils = Utils;
    })(Core = BP3D.Core || (BP3D.Core = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Core;
    (function (Core) {
        Core.dimInch = "inch";
        Core.dimFeet = "feet";
        Core.dimMeter = "m";
        Core.dimCentiMeter = "cm";
        Core.dimMilliMeter = "mm";
        var Dimensioning = (function () {
            function Dimensioning() { }
            Dimensioning.cmToMeasure = function (cm) {
                var msType = (($('option:selected').val() && $('option:selected').val() == "FT") ? Core.dimFeet : Core.dimInch);
                Core.Configuration.setValue('dimUnit', msType);
                switch (Core.Configuration.getStringValue(Core.configDimUnit)) {
                    case Core.dimInch:
                        var inches = Math.round((cm) / 2.54);
                        return inches + '"';
                    case Core.dimFeet:
                        var realFeet = ((cm * 0.393700) / 12);
                        var feet = Math.floor(realFeet);
                        var inches = Math.round((realFeet - feet) * 12);
                        return feet + "'" + inches + '"';
                    case Core.dimMilliMeter:
                        return "" + Math.round(10 * cm) + " mm";
                    case Core.dimCentiMeter:
                        return "" + Math.round(10 * cm) / 10 + " cm";
                    case Core.dimMeter:
                    default:
                        return "" + Math.round(10 * cm) / 1000 + " m";
                }
            };
            return Dimensioning;
        })();
        Core.Dimensioning = Dimensioning;
    })(Core = BP3D.Core || (BP3D.Core = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Core;
    (function (Core) {
        Core.configDimUnit = "dimUnit";
        Core.configWallHeight = "wallHeight";
        Core.configWallThickness = "wallThickness";
        var Configuration = (function () {
            function Configuration() { }
            Configuration.setValue = function (key, value) {
                this.data[key] = value;
            };
            Configuration.getStringValue = function (key) {
                switch (key) {
                    case Core.configDimUnit:
                        return this.data[key];
                    default:
                        throw new Error("Invalid string configuration parameter: " + key);
                }
            };
            Configuration.getNumericValue = function (key) {
                switch (key) {
                    case Core.configWallHeight:
                    case Core.configWallThickness:
                        return this.data[key];
                    default:
                        throw new Error("Invalid numeric configuration parameter: " + key);
                }
            };
            Configuration.data = {
                dimUnit: Core.dimInch,
                wallHeight: 244,
                wallThickness: 10
            };
            return Configuration;
        })();
        Core.Configuration = Configuration;
    })(Core = BP3D.Core || (BP3D.Core = {}));
})(BP3D || (BP3D = {}));
var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b)
        if (b.hasOwnProperty(p))
            d[p] = b[p];
    function __() {
        this.constructor = d;
    }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
};
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var Item = (function (_super) {
            __extends(Item, _super);
            function Item(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this);
                this.model = model;
                this.metadata = metadata;
                this.errorGlow = new THREE.Mesh();
                this.hover = false;
                this.selected = false;
                this.highlighted = false;
                this.error = false;
                this.emissiveColor = 0x444444;
                this.errorColor = 0xff0000;
                this.obstructFloorMoves = true;
                this.allowRotate = true;
                this.fixed = false;
                this.showMeasure = false;
                this.dragOffset = new THREE.Vector3();
                this.getHeight = function () {
                    return this.halfSize.y * 2.0;
                };
                this.getWidth = function () {
                    return this.halfSize.x * 2.0;
                };
                this.getDepth = function () {
                    return this.halfSize.z * 2.0;
                };
                this.initObject = function () {
                    this.placeInRoom();
                    this.scene.needsUpdate = true;
                };
                Item.prototype.scene = this.model.scene;
                this.geometry = geometry;
                this.material = material;
                this.errorColor = 0xff0000;
                this.resizable = metadata.resizable;
                this.castShadow = true;
                this.receiveShadow = false;
                this.geometry = geometry;
                this.material = material;
                if (position) {
                    this.position.copy(position);
                    this.position_set = true;
                } else {
                    this.position_set = false;
                }
                this.geometry.computeBoundingBox();
                this.geometry.applyMatrix(new THREE.Matrix4().makeTranslation(-0.5 * (this.geometry.boundingBox.max.x + this.geometry.boundingBox.min.x), -0.5 * (this.geometry.boundingBox.max.y + this.geometry.boundingBox.min.y), -0.5 * (this.geometry.boundingBox.max.z + this.geometry.boundingBox.min.z)));
                this.geometry.computeBoundingBox();
                this.halfSize = this.objectHalfSize();
                //                console.log('metadata.def_sizes', metadata.def_sizes)
                if (metadata.def_sizes)
                    this.resize(metadata.def_sizes.height, metadata.def_sizes.width, metadata.def_sizes.depth)
                if (rotation) {
                    this.rotation.y = rotation;
                }
                if (scale != null) {
                    this.setScale(scale.x, scale.y, scale.z);
                }
            };
            Item.prototype.remove = function () {
                console.log('itemremove', this)
                this.scene.removeItem(this);
            };;
            Item.prototype.resize = function (height, width, depth) {
                var x = width / this.getWidth();
                var y = height / this.getHeight();
                var z = depth / this.getDepth();
                this.setScale(x, y, z);
            };
            Item.prototype.setScale = function (x, y, z) {
                var scaleVec = new THREE.Vector3(x, y, z);
                this.halfSize.multiply(scaleVec);
                scaleVec.multiply(this.scale);
                this.scale.set(scaleVec.x, scaleVec.y, scaleVec.z);
                this.resized();
                this.scene.needsUpdate = true;
            };;
            Item.prototype.setFixed = function (fixed) {
                this.fixed = fixed;
            };
            Item.prototype.setMeasureView = function (measure) {
                this.showMeasure = measure;
            }
            Item.prototype.removed = function () { };
            Item.prototype.updateHighlight = function () {
                var on = this.hover || this.selected;
                this.highlighted = on;
                var hex = on ? this.emissiveColor : 0x000000;
                var hex = on ? this.emissiveColor : 0x333333;
                this.material.materials.forEach(function (material) {
                    material.emissive.setHex(hex);
                });
            };
            Item.prototype.mouseOver = function () {
                this.hover = true;
                this.updateHighlight();
            };;
            Item.prototype.mouseOff = function () {
                this.hover = false;
                this.updateHighlight();
            };;
            Item.prototype.setSelected = function () {
                this.selected = true;
                this.updateHighlight();
            };;
            Item.prototype.setUnselected = function () {
                this.selected = false;
                this.updateHighlight();
            };;
            Item.prototype.clickPressed = function (intersection) {
                this.dragOffset.copy(intersection.point).sub(this.position);
                // oldPos = selectedItem.position;
            };;
            Item.prototype.clickDragged = function (intersection) {
                if (intersection) {

                    // console.log('oldPos',oldPos)
                    this.moveToPosition(intersection.point.sub(this.dragOffset), intersection);
                }
            };
            Item.prototype.validatedOverlap = function (id, modal, vec3) {
                var items = modal.scene.getItems();
                let intersection = false;
                var draggingItem;
                let draggingBox = new THREE.Box3(new THREE.Vector3(), new THREE.Vector3())
                var storePos = []
                let boundingBox = 'bounding';
                let i = 0
                if (items.length > 1) {
                    items.forEach(res => {
                        if (res.material.uuid != id) {
                            i++
                            boundingBox = boundingBox + i
                            boundingBox = new THREE.Box3(new THREE.Vector3(), new THREE.Vector3())
                            boundingBox.setFromObject(res);
                            boundingBox.copy(res.geometry.boundingBox).applyMatrix4(res.matrixWorld)
                            storePos.push(boundingBox);
                        }
                        if (res.material.uuid == id) {
                            draggingItem = res;
                            draggingBox.setFromObject(draggingItem);
                            draggingBox.copy(draggingItem.geometry.boundingBox).applyMatrix4(draggingItem.matrixWorld)
                        }
                    })
                    let intersectingObj = new THREE.Box3(new THREE.Vector3(), new THREE.Vector3());
                    console.log('storePOs', storePos)
                    storePos.forEach((valPos, key) => {
                        if (draggingBox.isIntersectionBox(valPos)) {
                            intersection = true;
                            intersectingObj = valPos
                        }
                    })
                    return {
                        intersect: intersection,
                        pos: intersectingObj,
                        mPos: draggingBox
                    }
                } else
                    return {
                        intersect: intersection,
                        pos: {}
                    }
            }
            Item.prototype.rotate = function (intersection) {
                console.log('inter', intersection)
                if (intersection) {
                    var angle = BP3D.Core.Utils.angle(0, 1, intersection.point.x - this.position.x, intersection.point.z - this.position.z);
                    var snapTolerance = Math.PI / 16.0;
                    for (var i = -4; i <= 4; i++) {
                        if (Math.abs(angle - (i * (Math.PI / 2))) < snapTolerance) {
                            angle = i * (Math.PI / 2);
                            break;
                        }
                    }
                    this.rotation.y = angle;
                }
            };
            Item.prototype.moveToPosition = function (vec3, intersection) {
                this.position.copy(vec3);
            };
            Item.prototype.clickReleased = function () {
                if (this.error) {
                    this.hideError();
                }
                // detectchanges();
                // saveDesigns('auto-save');
                jsonstackindex = 0
                checkforCallback = 0
                oldPos = new THREE.Vector3();
            };;
            Item.prototype.customIntersectionPlanes = function () {
                return [];
            };
            Item.prototype.getCorners = function (xDim, yDim, position) {
                position = position || this.position;
                var halfSize = this.halfSize.clone();
                var c1 = new THREE.Vector3(-halfSize.x, 0, -halfSize.z);
                var c2 = new THREE.Vector3(halfSize.x, 0, -halfSize.z);
                var c3 = new THREE.Vector3(halfSize.x, 0, halfSize.z);
                var c4 = new THREE.Vector3(-halfSize.x, 0, halfSize.z);
                var transform = new THREE.Matrix4();
                transform.makeRotationY(this.rotation.y);
                c1.applyMatrix4(transform);
                c2.applyMatrix4(transform);
                c3.applyMatrix4(transform);
                c4.applyMatrix4(transform);
                c1.add(position);
                c2.add(position);
                c3.add(position);
                c4.add(position);
                var corners = [{
                    x: c1.x,
                    y: c1.z
                }, {
                    x: c2.x,
                    y: c2.z
                }, {
                    x: c3.x,
                    y: c3.z
                }, {
                    x: c4.x,
                    y: c4.z
                }
                ];
                return corners;
            };
            Item.prototype.showError = function (vec3) {
                vec3 = vec3 || this.position;
                if (!this.error) {
                    this.error = true;
                    this.errorGlow = this.createGlow(this.errorColor, 0.8, true);
                    this.scene.add(this.errorGlow);
                }
                this.errorGlow.position.copy(vec3);
            };
            Item.prototype.hideError = function () {
                if (this.error) {
                    this.error = false;
                    this.scene.remove(this.errorGlow);
                }
            };
            Item.prototype.objectHalfSize = function () {
                var objectBox = new THREE.Box3();
                objectBox.setFromObject(this);
                return objectBox.max.clone().sub(objectBox.min).divideScalar(2);
            };
            Item.prototype.createGlow = function (color, opacity, ignoreDepth) {
                ignoreDepth = ignoreDepth || false;
                opacity = opacity || 0.2;
                var glowMaterial = new THREE.MeshBasicMaterial({
                    color: color,
                    blending: THREE.AdditiveBlending,
                    opacity: 0.2,
                    transparent: true,
                    depthTest: !ignoreDepth
                });
                var glow = new THREE.Mesh(this.geometry.clone(), glowMaterial);
                glow.position.copy(this.position);
                glow.rotation.copy(this.rotation);
                glow.scale.copy(this.scale);
                return glow;
            };;
            return Item;
        })(THREE.Mesh);
        Items.Item = Item;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Model;
    (function (Model) {
        var cornerTolerance = 20;
        var Corner = (function () {
            function Corner(floorplan, x, y, id) {
                this.floorplan = floorplan;
                this.x = x;
                this.y = y;
                this.id = id;
                this.wallStarts = [];
                this.wallEnds = [];
                this.moved_callbacks = $.Callbacks();
                this.deleted_callbacks = $.Callbacks();
                this.action_callbacks = $.Callbacks();
                this.id = id || BP3D.Core.Utils.guid();
            }
            Corner.prototype.fireOnMove = function (func) {
                this.moved_callbacks.add(func);
            };
            Corner.prototype.fireOnDelete = function (func) {
                this.deleted_callbacks.add(func);
            };
            Corner.prototype.fireOnAction = function (func) {
                this.action_callbacks.add(func);
            };
            Corner.prototype.getX = function () {
                return this.x;
            };
            Corner.prototype.getY = function () {
                return this.y;
            };
            Corner.prototype.snapToAxis = function (tolerance) {
                var snapped = {
                    x: false,
                    y: false
                };
                var scope = this;
                this.adjacentCorners().forEach(function (corner) {
                    if (Math.abs(corner.x - scope.x) < tolerance) {
                        scope.x = corner.x;
                        snapped.x = true;
                    }
                    if (Math.abs(corner.y - scope.y) < tolerance) {
                        scope.y = corner.y;
                        snapped.y = true;
                    }
                });
                return snapped;
            };
            Corner.prototype.relativeMove = function (dx, dy) {
                this.move(this.x + dx, this.y + dy);
            };
            Corner.prototype.fireAction = function (action) {
                this.action_callbacks.fire(action);
            };
            Corner.prototype.remove = function () {
                this.deleted_callbacks.fire(this);
            };
            Corner.prototype.removeAll = function () {
                for (var i = 0; i < this.wallStarts.length; i++) {
                    this.wallStarts[i].remove();
                }
                for (var i = 0; i < this.wallEnds.length; i++) {
                    this.wallEnds[i].remove();
                }
                this.remove();
            };
            Corner.prototype.move = function (newX, newY) {
                this.x = newX;
                this.y = newY;
                this.mergeWithIntersected();
                this.moved_callbacks.fire(this.x, this.y);
                this.wallStarts.forEach(function (wall) {
                    wall.fireMoved();
                });
                this.wallEnds.forEach(function (wall) {
                    wall.fireMoved();
                });
            };
            Corner.prototype.adjacentCorners = function () {
                var retArray = [];
                for (var i = 0; i < this.wallStarts.length; i++) {
                    retArray.push(this.wallStarts[i].getEnd());
                }
                for (var i = 0; i < this.wallEnds.length; i++) {
                    retArray.push(this.wallEnds[i].getStart());
                }
                return retArray;
            };
            Corner.prototype.isWallConnected = function (wall) {
                for (var i = 0; i < this.wallStarts.length; i++) {
                    if (this.wallStarts[i] == wall) {
                        return true;
                    }
                }
                for (var i = 0; i < this.wallEnds.length; i++) {
                    if (this.wallEnds[i] == wall) {
                        return true;
                    }
                }
                return false;
            };
            Corner.prototype.distanceFrom = function (x, y) {
                var distance = BP3D.Core.Utils.distance(x, y, this.x, this.y);
                return distance;
            };
            Corner.prototype.distanceFromWall = function (wall) {
                return wall.distanceFrom(this.x, this.y);
            };
            Corner.prototype.distanceFromCorner = function (corner) {
                return this.distanceFrom(corner.x, corner.y);
            };
            Corner.prototype.detachWall = function (wall) {
                BP3D.Core.Utils.removeValue(this.wallStarts, wall);
                BP3D.Core.Utils.removeValue(this.wallEnds, wall);
                if (this.wallStarts.length == 0 && this.wallEnds.length == 0) {
                    this.remove();
                }
            };
            Corner.prototype.attachStart = function (wall) {
                this.wallStarts.push(wall);
            };
            Corner.prototype.attachEnd = function (wall) {
                this.wallEnds.push(wall);
            };
            Corner.prototype.wallTo = function (corner) {
                for (var i = 0; i < this.wallStarts.length; i++) {
                    if (this.wallStarts[i].getEnd() === corner) {
                        return this.wallStarts[i];
                    }
                }
                return null;
            };
            Corner.prototype.wallFrom = function (corner) {
                for (var i = 0; i < this.wallEnds.length; i++) {
                    if (this.wallEnds[i].getStart() === corner) {
                        return this.wallEnds[i];
                    }
                }
                return null;
            };
            Corner.prototype.wallToOrFrom = function (corner) {
                return this.wallTo(corner) || this.wallFrom(corner);
            };
            Corner.prototype.combineWithCorner = function (corner) {
                this.x = corner.x;
                this.y = corner.y;
                for (var i = corner.wallStarts.length - 1; i >= 0; i--) {
                    corner.wallStarts[i].setStart(this);
                }
                for (var i = corner.wallEnds.length - 1; i >= 0; i--) {
                    corner.wallEnds[i].setEnd(this);
                }
                corner.removeAll();
                this.removeDuplicateWalls();
                this.floorplan.update();
            };
            Corner.prototype.mergeWithIntersected = function () {
                for (var i = 0; i < this.floorplan.getCorners().length; i++) {
                    var corner = this.floorplan.getCorners()[i];
                    if (this.distanceFromCorner(corner) < cornerTolerance && corner != this) {
                        this.combineWithCorner(corner);
                        return true;
                    }
                }
                for (var i = 0; i < this.floorplan.getWalls().length; i++) {
                    var wall = this.floorplan.getWalls()[i];
                    if (this.distanceFromWall(wall) < cornerTolerance && !this.isWallConnected(wall)) {
                        var intersection = BP3D.Core.Utils.closestPointOnLine(this.x, this.y, wall.getStart().x, wall.getStart().y, wall.getEnd().x, wall.getEnd().y);
                        this.x = intersection.x;
                        this.y = intersection.y;
                        this.floorplan.newWall(this, wall.getEnd());
                        wall.setEnd(this);
                        this.floorplan.update();
                        return true;
                    }
                }
                return false;
            };
            Corner.prototype.removeDuplicateWalls = function () {
                var wallEndpoints = {};
                var wallStartpoints = {};
                for (var i = this.wallStarts.length - 1; i >= 0; i--) {
                    if (this.wallStarts[i].getEnd() === this) {
                        this.wallStarts[i].remove();
                    } else if (this.wallStarts[i].getEnd().id in wallEndpoints) {
                        this.wallStarts[i].remove();
                    } else {
                        wallEndpoints[this.wallStarts[i].getEnd().id] = true;
                    }
                }
                for (var i = this.wallEnds.length - 1; i >= 0; i--) {
                    if (this.wallEnds[i].getStart() === this) {
                        this.wallEnds[i].remove();
                    } else if (this.wallEnds[i].getStart().id in wallStartpoints) {
                        this.wallEnds[i].remove();
                    } else {
                        wallStartpoints[this.wallEnds[i].getStart().id] = true;
                    }
                }
            };
            return Corner;
        })();
        Model.Corner = Corner;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Model;
    (function (Model) {
        var HalfEdge = (function () {
            function HalfEdge(room, wall, front) {
                this.room = room;
                this.wall = wall;
                this.front = front;
                this.plane = null;
                this.interiorTransform = new THREE.Matrix4();
                this.invInteriorTransform = new THREE.Matrix4();
                this.exteriorTransform = new THREE.Matrix4();
                this.invExteriorTransform = new THREE.Matrix4();
                this.redrawCallbacks = $.Callbacks();
                this.generatePlane = function () {
                    function transformCorner(corner) {
                        return new THREE.Vector3(corner.x, 0, corner.y);
                    }
                    var v1 = transformCorner(this.interiorStart());
                    var v2 = transformCorner(this.interiorEnd());
                    var v3 = v2.clone();
                    v3.y = this.wall.height;
                    var v4 = v1.clone();
                    v4.y = this.wall.height;
                    var geometry = new THREE.Geometry();
                    geometry.vertices = [v1, v2, v3, v4];
                    geometry.faces.push(new THREE.Face3(0, 1, 2));
                    geometry.faces.push(new THREE.Face3(0, 2, 3));
                    geometry.computeFaceNormals();
                    geometry.computeBoundingBox();
                    this.plane = new THREE.Mesh(geometry, new THREE.MeshBasicMaterial());
                    this.plane.visible = false;
                    this.plane.edge = this;
                    this.computeTransforms(this.interiorTransform, this.invInteriorTransform, this.interiorStart(), this.interiorEnd());
                    this.computeTransforms(this.exteriorTransform, this.invExteriorTransform, this.exteriorStart(), this.exteriorEnd());
                };
                this.front = front || false;
                this.offset = wall.thickness / 2.0;
                this.height = wall.height;
                if (this.front) {
                    this.wall.frontEdge = this;
                } else {
                    this.wall.backEdge = this;
                }
            }
            HalfEdge.prototype.getTexture = function () {
                if (this.front) {
                    return this.wall.frontTexture;
                } else {
                    return this.wall.backTexture;
                }
            };
            HalfEdge.prototype.setTexture = function (textureUrl, textureStretch, textureScale) {
                var texture = {
                    url: textureUrl,
                    stretch: textureStretch,
                    scale: textureScale
                };
                if (this.front) {
                    this.wall.frontTexture = texture;
                } else {
                    this.wall.backTexture = texture;
                }
                this.redrawCallbacks.fire();
            };
            HalfEdge.prototype.setWallHt = function (ht) {
                //                console.log('this.wall', this.wall)
                this.wall.height = ht
                this.redrawCallbacks.fire();
            };
            HalfEdge.prototype.interiorDistance = function () {
                var start = this.interiorStart();
                var end = this.interiorEnd();
                return BP3D.Core.Utils.distance(start.x, start.y, end.x, end.y);
            };
            HalfEdge.prototype.computeTransforms = function (transform, invTransform, start, end) {
                var v1 = start;
                var v2 = end;
                var angle = BP3D.Core.Utils.angle(1, 0, v2.x - v1.x, v2.y - v1.y);
                var tt = new THREE.Matrix4();
                tt.makeTranslation(-v1.x, 0, -v1.y);
                var tr = new THREE.Matrix4();
                tr.makeRotationY(-angle);
                transform.multiplyMatrices(tr, tt);
                invTransform.getInverse(transform);
            };
            HalfEdge.prototype.distanceTo = function (x, y) {
                return BP3D.Core.Utils.pointDistanceFromLine(x, y, this.interiorStart().x, this.interiorStart().y, this.interiorEnd().x, this.interiorEnd().y);
            };
            HalfEdge.prototype.getStart = function () {
                if (this.front) {
                    return this.wall.getStart();
                } else {
                    return this.wall.getEnd();
                }
            };
            HalfEdge.prototype.getEnd = function () {
                if (this.front) {
                    return this.wall.getEnd();
                } else {
                    return this.wall.getStart();
                }
            };
            HalfEdge.prototype.getOppositeEdge = function () {
                if (this.front) {
                    return this.wall.backEdge;
                } else {
                    return this.wall.frontEdge;
                }
            };
            HalfEdge.prototype.interiorEnd = function () {
                var vec = this.halfAngleVector(this, this.next);
                return {
                    x: this.getEnd().x + vec.x,
                    y: this.getEnd().y + vec.y
                };
            };
            HalfEdge.prototype.interiorStart = function () {
                var vec = this.halfAngleVector(this.prev, this);
                return {
                    x: this.getStart().x + vec.x,
                    y: this.getStart().y + vec.y
                };
            };
            HalfEdge.prototype.interiorCenter = function () {
                return {
                    x: (this.interiorStart().x + this.interiorEnd().x) / 2.0,
                    y: (this.interiorStart().y + this.interiorEnd().y) / 2.0,
                };
            };
            HalfEdge.prototype.exteriorEnd = function () {
                var vec = this.halfAngleVector(this, this.next);
                return {
                    x: this.getEnd().x - vec.x,
                    y: this.getEnd().y - vec.y
                };
            };
            HalfEdge.prototype.exteriorStart = function () {
                var vec = this.halfAngleVector(this.prev, this);
                return {
                    x: this.getStart().x - vec.x,
                    y: this.getStart().y - vec.y
                };
            };
            HalfEdge.prototype.corners = function () {
                return [this.interiorStart(), this.interiorEnd(), this.exteriorEnd(), this.exteriorStart()];
            };
            HalfEdge.prototype.halfAngleVector = function (v1, v2) {
                if (!v1) {
                    var v1startX = v2.getStart().x - (v2.getEnd().x - v2.getStart().x);
                    var v1startY = v2.getStart().y - (v2.getEnd().y - v2.getStart().y);
                    var v1endX = v2.getStart().x;
                    var v1endY = v2.getStart().y;
                } else {
                    var v1startX = v1.getStart().x;
                    var v1startY = v1.getStart().y;
                    var v1endX = v1.getEnd().x;
                    var v1endY = v1.getEnd().y;
                }
                if (!v2) {
                    var v2startX = v1.getEnd().x;
                    var v2startY = v1.getEnd().y;
                    var v2endX = v1.getEnd().x + (v1.getEnd().x - v1.getStart().x);
                    var v2endY = v1.getEnd().y + (v1.getEnd().y - v1.getStart().y);
                } else {
                    var v2startX = v2.getStart().x;
                    var v2startY = v2.getStart().y;
                    var v2endX = v2.getEnd().x;
                    var v2endY = v2.getEnd().y;
                }
                var theta = BP3D.Core.Utils.angle2pi(v1startX - v1endX, v1startY - v1endY, v2endX - v1endX, v2endY - v1endY);
                var cs = Math.cos(theta / 2.0);
                var sn = Math.sin(theta / 2.0);
                var v2dx = v2endX - v2startX;
                var v2dy = v2endY - v2startY;
                var vx = v2dx * cs - v2dy * sn;
                var vy = v2dx * sn + v2dy * cs;
                var mag = BP3D.Core.Utils.distance(0, 0, vx, vy);
                var desiredMag = (this.offset) / sn;
                var scalar = desiredMag / mag;
                var halfAngleVector = {
                    x: vx * scalar,
                    y: vy * scalar
                };
                return halfAngleVector;
            };
            return HalfEdge;
        })();
        Model.HalfEdge = HalfEdge;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Model;
    (function (Model) {
        var defaultWallTexture = {
            url: "images/rooms/textures/wallmap.png",
            stretch: true,
            scale: 0
        };
        var Wall = (function () {
            function Wall(start, end) {
                this.start = start;
                this.end = end;
                this.frontEdge = null;
                this.backEdge = null;
                this.orphan = false;
                this.items = [];
                this.onItems = [];
                this.frontTexture = defaultWallTexture;
                this.backTexture = defaultWallTexture;
                this.thickness = BP3D.Core.Configuration.getNumericValue(BP3D.Core.configWallThickness);
                this.height = BP3D.Core.Configuration.getNumericValue(BP3D.Core.configWallHeight);
                this.moved_callbacks = $.Callbacks();
                this.deleted_callbacks = $.Callbacks();
                this.action_callbacks = $.Callbacks();
                this.id = this.getUuid();
                this.start.attachStart(this);
                this.end.attachEnd(this);
            }
            Wall.prototype.getUuid = function () {
                return [this.start.id, this.end.id].join();
            };
            Wall.prototype.resetFrontBack = function () {
                this.frontEdge = null;
                this.backEdge = null;
                this.orphan = false;
            };
            Wall.prototype.snapToAxis = function (tolerance) {
                this.start.snapToAxis(tolerance);
                this.end.snapToAxis(tolerance);
            };
            Wall.prototype.fireOnMove = function (func) {
                this.moved_callbacks.add(func);
            };
            Wall.prototype.fireOnDelete = function (func) {
                this.deleted_callbacks.add(func);
            };
            Wall.prototype.dontFireOnDelete = function (func) {
                this.deleted_callbacks.remove(func);
            };
            Wall.prototype.fireOnAction = function (func) {
                this.action_callbacks.add(func);
            };
            Wall.prototype.fireAction = function (action) {
                this.action_callbacks.fire(action);
            };
            Wall.prototype.relativeMove = function (dx, dy) {
                this.start.relativeMove(dx, dy);
                this.end.relativeMove(dx, dy);
            };
            Wall.prototype.fireMoved = function () {
                this.moved_callbacks.fire();
            };
            Wall.prototype.fireRedraw = function () {
                if (this.frontEdge) {
                    this.frontEdge.redrawCallbacks.fire();
                }
                if (this.backEdge) {
                    this.backEdge.redrawCallbacks.fire();
                }
            };
            Wall.prototype.getStart = function () {
                return this.start;
            };
            Wall.prototype.getEnd = function () {
                return this.end;
            };
            Wall.prototype.getStartX = function () {
                return this.start.getX();
            };
            Wall.prototype.getEndX = function () {
                return this.end.getX();
            };
            Wall.prototype.getStartY = function () {
                return this.start.getY();
            };
            Wall.prototype.getEndY = function () {
                return this.end.getY();
            };
            Wall.prototype.remove = function () {
                this.start.detachWall(this);
                this.end.detachWall(this);
                this.deleted_callbacks.fire(this);
            };
            Wall.prototype.setStart = function (corner) {
                this.start.detachWall(this);
                corner.attachStart(this);
                this.start = corner;
                this.fireMoved();
            };
            Wall.prototype.setEnd = function (corner) {
                this.end.detachWall(this);
                corner.attachEnd(this);
                this.end = corner;
                this.fireMoved();
            };
            Wall.prototype.distanceFrom = function (x, y) {
                return BP3D.Core.Utils.pointDistanceFromLine(x, y, this.getStartX(), this.getStartY(), this.getEndX(), this.getEndY());
            };
            Wall.prototype.oppositeCorner = function (corner) {
                if (this.start === corner) {
                    return this.end;
                } else if (this.end === corner) {
                    return this.start;
                } else {
                    console.log('Wall does not connect to corner');
                }
            };
            return Wall;
        })();
        Model.Wall = Wall;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Model;
    (function (Model) {
        var defaultRoomTexture = {
            url: "images/rooms/textures/hardwood.png",
            scale: 400
        };
        var Room = (function () {
            function Room(floorplan, corners) {
                this.floorplan = floorplan;
                this.corners = corners;
                this.interiorCorners = [];
                this.edgePointer = null;
                this.floorPlane = null;
                this.customTexture = false;
                this.floorChangeCallbacks = $.Callbacks();
                this.updateWalls();
                this.updateInteriorCorners();
                this.generatePlane();
            }
            Room.prototype.getUuid = function () {
                var cornerUuids = BP3D.Core.Utils.map(this.corners, function (c) {
                    return c.id;
                });
                cornerUuids.sort();
                return cornerUuids.join();
            };
            Room.prototype.fireOnFloorChange = function (callback) {
                this.floorChangeCallbacks.add(callback);
            };
            Room.prototype.getTexture = function () {
                var uuid = this.getUuid();
                var tex = this.floorplan.getFloorTexture(uuid);
                return tex || defaultRoomTexture;
            };
            Room.prototype.setTexture = function (textureUrl, textureStretch, textureScale) {
                var uuid = this.getUuid();
                this.floorplan.setFloorTexture(uuid, textureUrl, textureScale);
                this.floorChangeCallbacks.fire();
            };
            Room.prototype.generatePlane = function () {
                var points = [];
                this.interiorCorners.forEach(function (corner) {
                    points.push(new THREE.Vector2(corner.x, corner.y));
                });
                var shape = new THREE.Shape(points);
                var geometry = new THREE.ShapeGeometry(shape);
                this.floorPlane = new THREE.Mesh(geometry, new THREE.MeshBasicMaterial({
                    side: THREE.DoubleSide
                }));
                this.floorPlane.visible = false;
                this.floorPlane.rotation.set(Math.PI / 2, 0, 0);
                this.floorPlane.room = this;
            };
            Room.prototype.cycleIndex = function (index) {
                if (index < 0) {
                    return index += this.corners.length;
                } else {
                    return index % this.corners.length;
                }
            };
            Room.prototype.updateInteriorCorners = function () {
                var edge = this.edgePointer;
                while (true) {
                    this.interiorCorners.push(edge.interiorStart());
                    edge.generatePlane();
                    if (edge.next === this.edgePointer) {
                        break;
                    } else {
                        edge = edge.next;
                    }
                }
            };
            Room.prototype.updateWalls = function () {
                var prevEdge = null;
                var firstEdge = null;
                for (var i = 0; i < this.corners.length; i++) {
                    var firstCorner = this.corners[i];
                    var secondCorner = this.corners[(i + 1) % this.corners.length];
                    var wallTo = firstCorner.wallTo(secondCorner);
                    var wallFrom = firstCorner.wallFrom(secondCorner);
                    if (wallTo) {
                        var edge = new Model.HalfEdge(this, wallTo, true);
                    } else if (wallFrom) {
                        var edge = new Model.HalfEdge(this, wallFrom, false);
                    } else {
                        console.log("corners arent connected by a wall, uh oh");
                    }
                    if (i == 0) {
                        firstEdge = edge;
                    } else {
                        edge.prev = prevEdge;
                        prevEdge.next = edge;
                        if (i + 1 == this.corners.length) {
                            firstEdge.prev = edge;
                            edge.next = firstEdge;
                        }
                    }
                    prevEdge = edge;
                }
                this.edgePointer = firstEdge;
            };
            return Room;
        })();
        Model.Room = Room;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Model;
    (function (Model) {
        var defaultFloorPlanTolerance = 10.0;
        var Floorplan = (function () {
            function Floorplan() {
                this.walls = [];
                this.corners = [];
                this.rooms = [];
                this.new_wall_callbacks = $.Callbacks();
                this.new_corner_callbacks = $.Callbacks();
                this.redraw_callbacks = $.Callbacks();
                this.updated_rooms = $.Callbacks();
                this.roomLoadedCallbacks = $.Callbacks();
                this.floorTextures = {};
            }
            Floorplan.prototype.wallEdges = function () {
                var edges = [];
                this.walls.forEach(function (wall) {
                    if (wall.frontEdge) {
                        edges.push(wall.frontEdge);
                    }
                    if (wall.backEdge) {
                        edges.push(wall.backEdge);
                    }
                });
                return edges;
            };
            Floorplan.prototype.wallEdgePlanes = function () {
                var planes = [];
                this.walls.forEach(function (wall) {
                    if (wall.frontEdge) {
                        planes.push(wall.frontEdge.plane);
                    }
                    if (wall.backEdge) {
                        planes.push(wall.backEdge.plane);
                    }
                });
                return planes;
            };
            Floorplan.prototype.floorPlanes = function () {
                return BP3D.Core.Utils.map(this.rooms, function (room) {
                    return room.floorPlane;
                });
            };
            Floorplan.prototype.fireOnNewWall = function (callback) {
                this.new_wall_callbacks.add(callback);
            };
            Floorplan.prototype.fireOnNewCorner = function (callback) {
                this.new_corner_callbacks.add(callback);
            };
            Floorplan.prototype.fireOnRedraw = function (callback) {
                this.redraw_callbacks.add(callback);
            };
            Floorplan.prototype.fireOnUpdatedRooms = function (callback) {
                this.updated_rooms.add(callback);
            };
            Floorplan.prototype.newWall = function (start, end) {
                var wall = new Model.Wall(start, end);
                this.walls.push(wall);
                var scope = this;
                wall.fireOnDelete(function () {
                    scope.removeWall(wall);
                });
                this.new_wall_callbacks.fire(wall);
                this.update();
                return wall;
            };
            Floorplan.prototype.removeWall = function (wall) {
                BP3D.Core.Utils.removeValue(this.walls, wall);
                this.update();
            };
            Floorplan.prototype.newCorner = function (x, y, id) {
                var _this = this;
                var corner = new Model.Corner(this, x, y, id);
                this.corners.push(corner);
                corner.fireOnDelete(function () {
                    _this.removeCorner;
                });
                this.new_corner_callbacks.fire(corner);
                return corner;
            };
            Floorplan.prototype.removeCorner = function (corner) {
                BP3D.Core.Utils.removeValue(this.corners, corner);
            };
            Floorplan.prototype.getWalls = function () {
                return this.walls;
            };
            Floorplan.prototype.getCorners = function () {
                return this.corners;
            };
            Floorplan.prototype.getRooms = function () {
                return this.rooms;
            };
            Floorplan.prototype.overlappedCorner = function (x, y, tolerance) {
                tolerance = tolerance || defaultFloorPlanTolerance;
                for (var i = 0; i < this.corners.length; i++) {
                    if (this.corners[i].distanceFrom(x, y) < tolerance) {
                        return this.corners[i];
                    }
                }
                return null;
            };
            Floorplan.prototype.overlappedWall = function (x, y, tolerance) {
                tolerance = tolerance || defaultFloorPlanTolerance;
                for (var i = 0; i < this.walls.length; i++) {
                    if (this.walls[i].distanceFrom(x, y) < tolerance) {
                        return this.walls[i];
                    }
                }
                return null;
            };
            Floorplan.prototype.saveFloorplan = function () {
                var floorplan = {
                    corners: {},
                    walls: [],
                    wallTextures: [],
                    floorTextures: {},
                    newFloorTextures: {}
                };
                this.corners.forEach(function (corner) {
                    floorplan.corners[corner.id] = {
                        'x': corner.x,
                        'y': corner.y
                    };
                });
                this.walls.forEach(function (wall) {
                    floorplan.walls.push({
                        'corner1': wall.getStart().id,
                        'corner2': wall.getEnd().id,
                        'frontTexture': wall.frontTexture,
                        'backTexture': wall.backTexture
                    });
                });
                floorplan.newFloorTextures = this.floorTextures;
                return floorplan;
            };
            Floorplan.prototype.loadFloorplan = function (floorplan) {
                this.reset();
                var corners = {};
                if (floorplan == null || !('corners' in floorplan) || !('walls' in floorplan)) {
                    return;
                }
                for (var id in floorplan.corners) {
                    var corner = floorplan.corners[id];
                    corners[id] = this.newCorner(corner.x, corner.y, id);
                }
                var scope = this;
                floorplan.walls.forEach(function (wall) {
                    var newWall = scope.newWall(corners[wall.corner1], corners[wall.corner2]);
                    if (wall.frontTexture) {
                        newWall.frontTexture = wall.frontTexture;
                    }
                    if (wall.backTexture) {
                        newWall.backTexture = wall.backTexture;
                    }
                    // if (wall.height) {
                    //     newWall.height = wall.height;
                    // }
                    // if(!('height' in wall) && !('height' in newWall) ){
                    //     var wallht = Number($('#wall-ht select').val());
                    //     newWall.height = wallht;

                    // }
                    if (wall.height) {
                        newWall.height = wall.height;
                    }
                    if (!wall.height || !newWall.height) {
                        // console.log('Its inside',wall.height,newWall.height)
                        // }
                        // else{
                        //                        console.log("if", wall.height, newWall.height)
                        var wallht = Number($('#wall-ht select').val());
                        if ($('#wall-ht option:selected').text() == 'Custom Wall') {
                            if (APP_UOM == 'IN')
                                wallht *= 2.54;
                            else if (APP_UOM == 'FT')
                                wallht *= 30.58;
                        }
                        //                        console.log('*ht', wallht)
                        newWall.height = wallht;

                    }


                });
                if ('newFloorTextures' in floorplan) {
                    this.floorTextures = floorplan.newFloorTextures;
                }
                this.update();
                this.roomLoadedCallbacks.fire();
            };
            Floorplan.prototype.getFloorTexture = function (uuid) {
                if (uuid in this.floorTextures) {
                    return this.floorTextures[uuid];
                } else {
                    return null;
                }
            };
            Floorplan.prototype.setFloorTexture = function (uuid, url, scale) {
                this.floorTextures[uuid] = {
                    url: url,
                    scale: scale
                };
            };
            Floorplan.prototype.updateFloorTextures = function () {
                var uuids = BP3D.Core.Utils.map(this.rooms, function (room) {
                    return room.getUuid();
                });
                for (var uuid in this.floorTextures) {
                    if (!BP3D.Core.Utils.hasValue(uuids, uuid)) {
                        delete this.floorTextures[uuid];
                    }
                }
            };
            Floorplan.prototype.reset = function () {
                var tmpCorners = this.corners.slice(0);
                var tmpWalls = this.walls.slice(0);
                tmpCorners.forEach(function (corner) {
                    corner.remove();
                });
                tmpWalls.forEach(function (wall) {
                    wall.remove();
                });
                this.corners = [];
                this.walls = [];
            };
            Floorplan.prototype.update = function () {
                this.walls.forEach(function (wall) {
                    wall.resetFrontBack();
                });
                var roomCorners = this.findRooms(this.corners);
                this.rooms = [];
                var scope = this;
                roomCorners.forEach(function (corners) {
                    scope.rooms.push(new Model.Room(scope, corners));
                });
                this.assignOrphanEdges();
                this.updateFloorTextures();
                this.updated_rooms.fire();
            };
            Floorplan.prototype.getCenter = function () {
                return this.getDimensions(true);
            };
            Floorplan.prototype.getSize = function () {
                return this.getDimensions(false);
            };
            Floorplan.prototype.getDimensions = function (center) {
                center = center || false;
                var xMin = Infinity;
                var xMax = -Infinity;
                var zMin = Infinity;
                var zMax = -Infinity;
                this.corners.forEach(function (corner) {
                    if (corner.x < xMin)
                        xMin = corner.x;
                    if (corner.x > xMax)
                        xMax = corner.x;
                    if (corner.y < zMin)
                        zMin = corner.y;
                    if (corner.y > zMax)
                        zMax = corner.y;
                });
                var ret;
                if (xMin == Infinity || xMax == -Infinity || zMin == Infinity || zMax == -Infinity) {
                    ret = new THREE.Vector3();
                } else {
                    if (center) {
                        ret = new THREE.Vector3((xMin + xMax) * 0.5, 0, (zMin + zMax) * 0.5);
                    } else {
                        ret = new THREE.Vector3((xMax - xMin), 0, (zMax - zMin));
                    }
                }
                return ret;
            };
            Floorplan.prototype.assignOrphanEdges = function () {
                var orphanWalls = [];
                this.walls.forEach(function (wall) {
                    if (!wall.backEdge && !wall.frontEdge) {
                        wall.orphan = true;
                        var back = new Model.HalfEdge(null, wall, false);
                        back.generatePlane();
                        var front = new Model.HalfEdge(null, wall, true);
                        front.generatePlane();
                        orphanWalls.push(wall);
                    }
                });
            };
            Floorplan.prototype.findRooms = function (corners) {
                function _calculateTheta(previousCorner, currentCorner, nextCorner) {
                    var theta = BP3D.Core.Utils.angle2pi(previousCorner.x - currentCorner.x, previousCorner.y - currentCorner.y, nextCorner.x - currentCorner.x, nextCorner.y - currentCorner.y);
                    return theta;
                }
                function _removeDuplicateRooms(roomArray) {
                    var results = [];
                    var lookup = {};
                    var hashFunc = function (corner) {
                        return corner.id;
                    };
                    var sep = '-';
                    for (var i = 0; i < roomArray.length; i++) {
                        var add = true;
                        var room = roomArray[i];
                        for (var j = 0; j < room.length; j++) {
                            var roomShift = BP3D.Core.Utils.cycle(room, j);
                            var str = BP3D.Core.Utils.map(roomShift, hashFunc).join(sep);
                            if (lookup.hasOwnProperty(str)) {
                                add = false;
                            }
                        }
                        if (add) {
                            results.push(roomArray[i]);
                            lookup[str] = true;
                        }
                    }
                    return results;
                }
                function _findTightestCycle(firstCorner, secondCorner) {
                    var stack = [];
                    var next = {
                        corner: secondCorner,
                        previousCorners: [firstCorner]
                    };
                    var visited = {};
                    visited[firstCorner.id] = true;
                    while (next) {
                        var currentCorner = next.corner;
                        visited[currentCorner.id] = true;
                        if (next.corner === firstCorner && currentCorner !== secondCorner) {
                            return next.previousCorners;
                        }
                        var addToStack = [];
                        var adjacentCorners = next.corner.adjacentCorners();
                        for (var i = 0; i < adjacentCorners.length; i++) {
                            var nextCorner = adjacentCorners[i];
                            if (nextCorner.id in visited && !(nextCorner === firstCorner && currentCorner !== secondCorner)) {
                                continue;
                            }
                            addToStack.push(nextCorner);
                        }
                        var previousCorners = next.previousCorners.slice(0);
                        previousCorners.push(currentCorner);
                        if (addToStack.length > 1) {
                            var previousCorner = next.previousCorners[next.previousCorners.length - 1];
                            addToStack.sort(function (a, b) {
                                return (_calculateTheta(previousCorner, currentCorner, b) -
                                    _calculateTheta(previousCorner, currentCorner, a));
                            });
                        }
                        if (addToStack.length > 0) {
                            addToStack.forEach(function (corner) {
                                stack.push({
                                    corner: corner,
                                    previousCorners: previousCorners
                                });
                            });
                        }
                        next = stack.pop();
                    }
                    return [];
                }
                var loops = [];
                corners.forEach(function (firstCorner) {
                    firstCorner.adjacentCorners().forEach(function (secondCorner) {
                        loops.push(_findTightestCycle(firstCorner, secondCorner));
                    });
                });
                var uniqueLoops = _removeDuplicateRooms(loops);
                var uniqueCCWLoops = BP3D.Core.Utils.removeIf(uniqueLoops, BP3D.Core.Utils.isClockwise);
                return uniqueCCWLoops;
            };
            return Floorplan;
        })();
        Model.Floorplan = Floorplan;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var FloorItem = (function (_super) {
            __extends(FloorItem, _super);
            function FloorItem(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this, model, metadata, geometry, material, position, rotation, scale);
            };
            FloorItem.prototype.placeInRoom = function () {
                if (!this.position_set) {
                    var center = this.model.floorplan.getCenter();
                    this.position.x = center.x;
                    this.position.z = center.z;
                    this.position.y = 0.5 * (this.geometry.boundingBox.max.y - this.geometry.boundingBox.min.y);
                }
            };;
            FloorItem.prototype.resized = function () {
                this.position.y = this.halfSize.y;
            };
            var oldPos = new THREE.Vector3();
            FloorItem.prototype.moveToPosition = function (vec3, intersection) {
                if (!this.isValidPosition(vec3)) {
                    this.showError(vec3);
                    return;
                } else {
                    let min = new THREE.Vector3(vec3.x - this.halfSize.x, vec3.y - this.halfSize.y, vec3.z - this.halfSize.z);
                    let max = new THREE.Vector3(vec3.x + this.halfSize.x, vec3.y + this.halfSize.y, vec3.z + this.halfSize.z);
                    let boundBox = new THREE.Box3(min, max)
                    B3D.model.checkforvalidPos(boundBox, this.material.uuid).then(data => {
                        console.log('daa',data)
                        if(data.isInter){
                            vec3.y = this.position.y;
                            this.showError(vec3)
                            this.position.copy(oldPos);
                        }
                        else {
                            this.hideError();
                            vec3.y = this.position.y;
                            this.position.copy(vec3);
                            oldPos.x = vec3.x;
                            oldPos.y = vec3.y;
                            oldPos.z = vec3.z;
                        }
                    })

                }
            };


            FloorItem.prototype.isValidPosition = function (vec3) {
                var corners = this.getCorners('x', 'z', vec3);
                var rooms = this.model.floorplan.getRooms();
                var isInARoom = false;
                for (var i = 0; i < rooms.length; i++) {
                    if (BP3D.Core.Utils.pointInPolygon(vec3.x, vec3.z, rooms[i].interiorCorners) && !BP3D.Core.Utils.polygonPolygonIntersect(corners, rooms[i].interiorCorners)) {
                        isInARoom = true;
                    }
                }
                if (!isInARoom) {
                    return false;
                }
                return true;
            };
            return FloorItem;
        })(Items.Item);
        Items.FloorItem = FloorItem;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var is_default = false;
        var metaData;
        var HalfSize;
        var Material;
        var WallItem = (function (_super) {
            __extends(WallItem, _super);
            function WallItem(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this, model, metadata, geometry, material, position, rotation, scale);
                this.currentWallEdge = null;
                this.refVec = new THREE.Vector2(0, 1.0);
                this.wallOffsetScalar = 0;
                this.sizeX = 0;
                this.sizeY = 0;
                is_default = metadata.is_default
                this.addToWall = false;
                this.boundToFloor = false;
                this.frontVisible = false;
                this.backVisible = false;
                this.allowRotate = false;
                metaData = metadata
                HalfSize = this.halfSize;
                Material = material;

                if (metadata.itemName == 'BCCLR') {
                    this.allowRotate = true;

                }

            };
            WallItem.prototype.setScale = function (x, y, z) {
                var scaleVec = new THREE.Vector3(x, y, z);
                this.halfSize.multiply(scaleVec);
                scaleVec.multiply(this.scale);
                this.scale.set(scaleVec.x, scaleVec.y, scaleVec.z);
                this.resized();
                this.scene.needsUpdate = true;
            };;
            WallItem.prototype.closestWallEdge = function () {
                var wallEdges = this.model.floorplan.wallEdges();
                var wallEdge = null;
                var minDistance = null;
                var itemX = this.position.x;
                var itemZ = this.position.z;
                wallEdges.forEach(function (edge) {
                    var distance = edge.distanceTo(itemX, itemZ);
                    if (minDistance === null || distance < minDistance) {
                        minDistance = distance;
                        wallEdge = edge;
                    }
                });
                return wallEdge;
            };
            WallItem.prototype.removed = function () {
                BP3D.Core.Utils.removeValue(this.currentWallEdge.wall.items, this);
                BP3D.Core.Utils.removeValue(this.currentWallEdge.wall.onItems, this);
                this.redrawWall();
            };
            WallItem.prototype.redrawWall = function () {
                if (this.addToWall) {
                    this.currentWallEdge.wall.fireRedraw();
                }
            };
            WallItem.prototype.updateEdgeVisibility = function (visible, front) {
                if (front) {
                    this.frontVisible = visible;
                } else {
                    this.backVisible = visible;
                }
                this.visible = (this.frontVisible || this.backVisible);
            };
            WallItem.prototype.updateSize = function () {
                this.wallOffsetScalar = (this.geometry.boundingBox.max.z - this.geometry.boundingBox.min.z) * this.scale.z / 2.0;
                this.sizeX = (this.geometry.boundingBox.max.x - this.geometry.boundingBox.min.x) * this.scale.x;
                this.sizeY = (this.geometry.boundingBox.max.y - this.geometry.boundingBox.min.y) * this.scale.y;
            };
            WallItem.prototype.resized = function () {
                if (this.boundToFloor) {
                    this.position.y = 0.5 * (this.geometry.boundingBox.max.y - this.geometry.boundingBox.min.y) * this.scale.y + 0.01;
                }
                this.updateSize();
                this.redrawWall();
            };
            WallItem.prototype.updateTexture = function (mesh, path, text) {
                mesh.material.materials.forEach((mat, key) => {
                    if (mat.name == 'outer_cab') {
                        if (text.trim() == 'None') {
                            mat.transparent = true;
                            mat.blending = THREE.MultiplyBlending
                            mat.emissive.setHex(0x444444)
                            mat.color.setHex(0x999999)
                            mat.map = new THREE.ImageUtils.loadTexture(path);
                            mat.needsUpdate = true;
                        } else {
                            mat.transparent = false;
                            mat.blending = THREE.NormalBlending
                            mat.ambient.setHex(0xffffff);
                            mat.depthWrite = true;
                            mat.depthTest = true;
                            mat.color.setHex(0xffffff)
                            mat.map = new THREE.ImageUtils.loadTexture(path);
                            mat.map.wrapT = THREE.RepeatWrapping;
                            mat.map.wrapS = THREE.RepeatWrapping;
                            mat.map.repeat.set(0.9, 0.9);
                            mat.needsUpdate = true;

                        }
                    }
                })
                B3D.model.floorplan.update();
            }
            WallItem.prototype.placeInRoom = function () {
                var closestWallEdge = this.closestWallEdge();
                if (!is_default)
                    closestWallEdge = this.setWallEdge()
                this.changeWallEdge(closestWallEdge);
                this.updateSize();
                if (!this.position_set) {
                    let boxpos = new THREE.Vector3()
                    this.boundMove(boxpos);
                    this.position.copy(boxpos);
                    this.redrawWall();
                }
            };
            WallItem.prototype.setWallEdge = function () {
                var halfEdge = [];
                var wall_list = this.model.floorplan.getWalls();
                var selectedWallVal = JSON.parse($('#change-wall').val());
                wall_list.forEach(item => {
                    if (item.id == selectedWallVal.id)
                        halfEdge = item.backEdge
                })
                return halfEdge;
            }
            // var oldPos = new THREE.Vector3()

            WallItem.prototype.moveToPosition = function (vec3, intersection) {
                
                oldPos = selectedItem.position
                let selectedwall = JSON.parse($('#change-wall').val())
                let incType = 'z';
                let min,max;
                if (Math.abs(selectedwall.interiorStart.x-selectedwall.interiorEnd.x)+10>selectedwall.total_space && (Math.abs(selectedwall.interiorStart.x-selectedwall.interiorEnd.x)-10<selectedwall.total_space))
                incType = 'x';
                if(incType=='x')
                {
                    if(selectedwall.interiorEnd.x-selectedwall.interiorStart.x>0){
                        min = new THREE.Vector3(vec3.x - this.halfSize.x, vec3.y - this.halfSize.y, selectedwall.interiorEnd.y);
                        max = new THREE.Vector3(vec3.x + this.halfSize.x, vec3.y + this.halfSize.y, min.x + 2*this.halfSize.z);
                    }
                    else
                    {
                        min = new THREE.Vector3(vec3.x - this.halfSize.x, vec3.y - this.halfSize.y, selectedwall.interiorEnd.y - this.halfSize.z);
                        max = new THREE.Vector3(vec3.x + this.halfSize.x, vec3.y + this.halfSize.y, selectedwall.interiorEnd.y );
                    }
                    
                   
                }
               
                  
               
                   if(incType=='z'){
                    if(selectedwall.interiorEnd.y-selectedwall.interiorStart.y>0)
                   {
                    min = new THREE.Vector3(selectedwall.interiorEnd.x-2*this.halfSize.z, vec3.y - this.halfSize.y, vec3.z - this.halfSize.x);
                   max = new THREE.Vector3(selectedwall.interiorEnd.x, vec3.y + this.halfSize.y, vec3.z + this.halfSize.x);
                }
                   else{
                    console.log('inside')
                    min = new THREE.Vector3(selectedwall.interiorEnd.x , vec3.y - this.halfSize.y, vec3.z - this.halfSize.x);
                    max = new THREE.Vector3(selectedwall.interiorEnd.x+ 2*this.halfSize.z, vec3.y + this.halfSize.y, vec3.z + this.halfSize.x);
                }
                if(min.x<0 || max.x<0){
                    min.x = 0
                    max.x = 2*this.halfSize.z
                }
                }
                if(metaData.itemType==9 || metaData.itemType==7){
                    min.y=0;
                    max.y=2*this.halfSize.y;
                }
                // if((min.x<0 || max.x<0 || max.x>selectedwall.interiorEnd.x  ) && metaData.itemType==7|| metaData.itemType==3 && incType=='z' ){
                   
                //     else if(max.x>selectedwall.interiorEnd.x){
                //         max.x=selectedwall.interiorEnd.x
                //         min.x = max.x - 2*this.halfSize.z
                //     } 
                // }
                console.log('incType',incType)
                let boundBox = new THREE.Box3(min, max)
                B3D.model.checkforvalidPos(boundBox, this.material.uuid).then(data => {
                    if (data.isInter) {
                        console.log('data',data)
                        // this.changeWallEdge(intersection.object.edge);
                        updateItemWallname(intersection.object.edge).then(_=>{
                            this.showError(vec3)
                            this.boundMove(vec3);
                            this.position.copy(oldPos);
                            this.redrawWall();
                        })
                       
                    }
                    else {
                        this.hideError();
                        this.changeWallEdge(intersection.object.edge);
                        updateItemWallname(intersection.object.edge).then(_=>{
                            this.boundMove(vec3);
                            this.position.copy(vec3);
                            this.redrawWall();
                            oldPos.x = vec3.x;
                            oldPos.y = vec3.y;
                            oldPos.z = vec3.z;
                        })
                      

                    }

                })
              
            }

            function updateItemWallname(backedge) {
                // console.log('backwalledge', backedge.wall)
                return new Promise(resolve=>{
                    mes_info.forEach(mes => {
                        if (mes.id == backedge.wall.id) {
                            selectedItem.wallId = backedge.wall.id
                            setWallname = JSON.stringify(mes)
                            $('#change-wall').val(setWallname)
                            window.buildWallInfoHTML(setWallname)
                            wallID = mes.id
                            setWallname = JSON.parse(JSON.stringify(mes))
                            B3D.three.setWallName(setWallname);
    
                        }
                        window.getMeasureInfo(B3D);
                    })
                    resolve([])
                })
             

            }
            WallItem.prototype.getWallOffset = function () {
                return this.wallOffsetScalar;
            };
            WallItem.prototype.changeWallEdge = function (wallEdge) {
                if (this.currentWallEdge != null) {
                    if (this.addToWall) {
                        BP3D.Core.Utils.removeValue(this.currentWallEdge.wall.items, this);
                        this.redrawWall();
                    } else {
                        BP3D.Core.Utils.removeValue(this.currentWallEdge.wall.onItems, this);
                    }
                }
                if (this.currentWallEdge != null) {
                    this.currentWallEdge.wall.dontFireOnDelete(this.remove.bind(this));
                }
                wallEdge.wall.fireOnDelete(this.remove.bind(this));
                var normal2 = new THREE.Vector2();
                var normal3 = wallEdge.plane.geometry.faces[0].normal;
                normal2.x = normal3.x;
                normal2.y = normal3.z;
                var angle = BP3D.Core.Utils.angle(this.refVec.x, this.refVec.y, normal2.x, normal2.y);
                this.rotation.y = angle;
                this.currentWallEdge = wallEdge;
                if (this.addToWall) {
                    wallEdge.wall.items.push(this);
                    this.redrawWall();
                } else {
                    wallEdge.wall.onItems.push(this);
                }
            };
            WallItem.prototype.customIntersectionPlanes = function () {



                return this.model.floorplan.wallEdgePlanes();
            };
            WallItem.prototype.boundMove = function (vec3) {
                var tolerance = 1;
                var edge = this.currentWallEdge;
                vec3.applyMatrix4(edge.interiorTransform);
                if (vec3.x < this.sizeX / 2.0 + tolerance) {
                    vec3.x = this.sizeX / 2.0 + tolerance;
                } else if (vec3.x > (edge.interiorDistance() - this.sizeX / 2.0 - tolerance)) {
                    vec3.x = edge.interiorDistance() - this.sizeX / 2.0 - tolerance;
                }
                if (this.boundToFloor) {
                    vec3.y = 0.5 * (this.geometry.boundingBox.max.y - this.geometry.boundingBox.min.y) * this.scale.y + 0.01;
                } else {
                    if (vec3.y < this.sizeY / 2.0 + tolerance) {
                        vec3.y = this.sizeY / 2.0 + tolerance;
                    } else if (vec3.y > edge.height - this.sizeY / 2.0 - tolerance) {
                        vec3.y = edge.height - this.sizeY / 2.0 - tolerance;
                    }
                }
                vec3.z = this.getWallOffset();
                vec3.applyMatrix4(edge.invInteriorTransform);
            };
            return WallItem;
        })(Items.Item);
        Items.WallItem = WallItem;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var InWallItem = (function (_super) {
            __extends(InWallItem, _super);
            function InWallItem(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this, model, metadata, geometry, material, position, rotation, scale);
                this.addToWall = true;
            };
            InWallItem.prototype.getWallOffset = function () {
                return -this.currentWallEdge.offset + 0.5;
            };
            return InWallItem;
        })(Items.WallItem);
        Items.InWallItem = InWallItem;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var InWallFloorItem = (function (_super) {
            __extends(InWallFloorItem, _super);
            function InWallFloorItem(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this, model, metadata, geometry, material, position, rotation, scale);
                this.boundToFloor = true;
            };
            return InWallFloorItem;
        })(Items.InWallItem);
        Items.InWallFloorItem = InWallFloorItem;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var OnFloorItem = (function (_super) {
            __extends(OnFloorItem, _super);
            function OnFloorItem(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this, model, metadata, geometry, material, position, rotation, scale);
                this.obstructFloorMoves = false;
                this.receiveShadow = true;
            };
            return OnFloorItem;
        })(Items.FloorItem);
        Items.OnFloorItem = OnFloorItem;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var WallFloorItem = (function (_super) {
            __extends(WallFloorItem, _super);
            function WallFloorItem(model, metadata, geometry, material, position, rotation, scale) {
                _super.call(this, model, metadata, geometry, material, position, rotation, scale);
                this.boundToFloor = true;
            };
            return WallFloorItem;
        })(Items.WallItem);
        Items.WallFloorItem = WallFloorItem;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Items;
    (function (Items) {
        var item_types = {
            1: Items.FloorItem,
            2: Items.WallItem,
            3: Items.InWallItem,
            7: Items.InWallFloorItem,
            8: Items.OnFloorItem,
            9: Items.WallFloorItem
        };
        var Factory = (function () {
            function Factory() { }
            Factory.getClass = function (itemType) {
                return item_types[itemType];
            };
            return Factory;
        })();
        Items.Factory = Factory;
    })(Items = BP3D.Items || (BP3D.Items = {}));
})(BP3D || (BP3D = {}));
var BP3D;
// import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
(function (BP3D) {
    var Model;
    (function (Model) {
        var Scene = (function () {
            function Scene(model, textureDir) {
                this.model = model;
                this.textureDir = textureDir;
                this.items = [];
                this.needsUpdate = false;
                this.itemLoadingCallbacks = $.Callbacks();
                this.itemLoadedCallbacks = $.Callbacks();
                this.itemRemovedCallbacks = $.Callbacks();
                this.scene = new THREE.Scene();
                this.loader = new THREE.JSONLoader();

                this.loader.crossOrigin = "";
            }
            Scene.prototype.add = function (mesh) {
                this.scene.add(mesh);
            };
            Scene.prototype.remove = function (mesh) {
                this.scene.remove(mesh);
                BP3D.Core.Utils.removeValue(this.items, mesh);
            };
            Scene.prototype.getScene = function () {
                return this.scene;
            };
            Scene.prototype.getItems = function () {
                return this.items;
            };
            Scene.prototype.itemCount = function () {
                return this.items.length;
            };
            Scene.prototype.clearItems = function () {
                var items_copy = this.items;
                var scope = this;
                this.items.forEach(function (item) {
                    scope.removeItem(item, true);
                });
                this.items = [];
            };


            Scene.prototype.removeItem = function (item, dontRemove) {
                console.log('item***', item, this.items)
                dontRemove = dontRemove || false;
//                this.itemRemovedCallbacks.fire(item);
                item.removed();
                this.scene.remove(item);
                if (!dontRemove) {
                    BP3D.Core.Utils.removeValue(this.items, item);
                }
            };
            Scene.prototype.addItem = function (itemType, fileName, metadata, textureInfo = {}, position, rotation, scale, fixed, show_measure) {
                //   console.log('scene',itemType, fileName, metadata,textureInfo, position, rotation, scale, fixed, show_measure)
                // console.log('GLTFLoader,',GLTFLoader)
                var item
                itemType = itemType || 1;
                var scope = this;
                var loaderCallback = function (geometry, materials) {

                    item = new (BP3D.Items.Factory.getClass(itemType))(scope.model, metadata, geometry, new THREE.MeshFaceMaterial(materials), position, rotation, scale);

                    // console.log('metadata',metadata)
                    item.fixed = fixed || false;
                    item.show_measure = show_measure || false;

                    let res = item;
                    console.log(textureInfo)
                    if (Object.keys(textureInfo).length && textureInfo.textureUrl != '') {
                        item['textureUrl'] = textureInfo.textureUrl;
                        item['textureName'] = textureInfo.textureName;
                        item['prev_textureUrl'] = textureInfo.textureUrl;
                        item['prev_textureName'] = textureInfo.textureName;


                        item.updateTexture(item, textureInfo.textureUrl, textureInfo.textureName);
                        if (textureInfo.hasOwnProperty('uuid') && textureInfo.hasOwnProperty('stackIndex')) {
                            let res = item
                            Object.keys(res).forEach((key, val) => {
                                Object.keys(textureInfo.meshprop).forEach((key1, val1) => {
                                    if (key == key1) {
                                        console.log('key', key, textureInfo.meshprop[key1])
                                        item[key] = textureInfo.meshprop[key1]
                                    }
                                })
                            })
                            console.log('item**12', item)
                            // item['uuid'] = textureInfo.uuid;
                            // // let jsonSta=item;
                            // jsonstack[textureInfo.stackIndex] = item;
                            // jsonstack.map(obj => {
                            //     if (obj.uuid == textureInfo.uuid) {
                            //         obj.material = item.material
                            //     }
                            // });
                            //  jsonstack[textureInfo.stackIndex] = res;
                            // item= textureInfo.meshprop
                            jsonstack[textureInfo.stackIndex] = {}
                            jsonstack[textureInfo.stackIndex] = item
                        }
                    }

                    if (textureInfo.hasOwnProperty('uuid') && textureInfo.hasOwnProperty('stackIndex')) {
                        scope.items.push(jsonstack[textureInfo.stackIndex]);
                        console.log('itesmm', jsonstack[textureInfo.stackIndex])
                        scope.add(jsonstack[textureInfo.stackIndex]);

                        jsonstack[textureInfo.stackIndex].initObject();
                        scope.itemRemovedCallbacks.fire(item)
                        scope.itemLoadedCallbacks.fire(jsonstack[textureInfo.stackIndex]);
                    }

                    else {
                        scope.items.push(item)
                        scope.add(item);

                        item.initObject();

                        scope.itemLoadedCallbacks.fire(item);
                    }


                };
                // if (BP3D.Items.Factory.getClass(itemType).name == 'OnFloorItem') {
                //     var rooms = scope.model.floorplan.getRooms()
                //         console.log('rooms', rooms)
                // }
                this.itemLoadingCallbacks.fire();
                this.loader.load(fileName, loaderCallback, this.textureDir);


            };
            return Scene;
        })();
        Model.Scene = Scene;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Model;
    (function (Model_1) {
        var Model = (function () {
            function Model(textureDir) {
                this.roomLoadingCallbacks = $.Callbacks();
                this.roomLoadedCallbacks = $.Callbacks();
                this.roomSavedCallbacks = $.Callbacks();
                this.roomDeletedCallbacks = $.Callbacks();
                this.floorplan = new Model_1.Floorplan();
                this.scene = new Model_1.Scene(this, textureDir);
            }
            Model.prototype.loadSerialized = function (json) {
                this.roomLoadingCallbacks.fire();
                var data = JSON.parse(json);
                this.newRoom(data.floorplan, data.items);
                this.roomLoadedCallbacks.fire();
            };
            Model.prototype.checkforvalidPos = function (boundbox, id = '') {

                return new Promise(resolve => {
                    var items = B3D.model.scene.getItems();
                    let intersection = false;
                    var storePos = []
                    let boundingBox = 'bounding';
                    let i = 0
                    if (items.length) {
                        items.forEach(res => {
                            // console.log('id',id,res.material.uuid)
                            // if (res.material.uuid != id) {

                            // }
                            if (id != '' && res.material.uuid != id) {
                                i++
                                boundingBox = boundingBox + i
                                boundingBox = new THREE.Box3(new THREE.Vector3(), new THREE.Vector3())
                                boundingBox.setFromObject(res);
                                boundingBox.copy(res.geometry.boundingBox).applyMatrix4(res.matrixWorld)
                                storePos.push(boundingBox);
                            }
                            else if (id == '') {
                                i++
                                boundingBox = boundingBox + i
                                boundingBox = new THREE.Box3(new THREE.Vector3(), new THREE.Vector3())
                                boundingBox.setFromObject(res);
                                boundingBox.copy(res.geometry.boundingBox).applyMatrix4(res.matrixWorld)
                                storePos.push(boundingBox);
                            }
                        })
                        let res = {}
                        if (storePos.length) {
                            for (i = storePos.length - 1; i >= 0; i--) {
                                console.log('check', boundbox, storePos[i])

                                if (boundbox.isIntersectionBox(storePos[i]) && intersection == false) {

                                    intersection = true;
                                    let position = new THREE.Vector3();
                                    position.x = (storePos[i].min.x + storePos[i].max.x) / 2
                                    position.y = (storePos[i].min.y + storePos[i].max.y) / 2
                                    position.z = (storePos[i].min.z + storePos[i].max.z) / 2
                                    // console.log(' intersection', { isInter: true, pos: position, boundBox: storePos[i] })
                                    res = { isInter: true, pos: position, boundBox: storePos[i] }
                                }
                                else if (intersection == false) {
                                    // console.log('boundBOx-', boundbox)
                                    intersection = false;
                                    let position = new THREE.Vector3();
                                    position.x = (boundbox.min.x + boundbox.max.x) / 2
                                    position.y = (boundbox.min.y + boundbox.max.y) / 2
                                    position.z = (boundbox.min.z + boundbox.max.z) / 2
                                    res = { isInter: false, pos: position, boundBox: boundbox }
                                }
                            }
                            resolve(res)
                        }
                        else {
                            // let pos= boundbox.getCenter()

                            console.log('boin', boundbox)
                            let position = new THREE.Vector3();
                            position.x = (boundbox.min.x + boundbox.max.x) / 2
                            position.y = (boundbox.min.y + boundbox.max.y) / 2
                            position.z = (boundbox.min.z + boundbox.max.z) / 2

                            console.log('boin', position)
                            resolve({ isInter: false, pos: position, boundBox: boundbox })
                        }


                    }
                    else {
                        // let pos= boundbox.getCenter()

                        console.log('boin', boundbox)
                        let position = new THREE.Vector3();
                        position.x = (boundbox.min.x + boundbox.max.x) / 2
                        position.y = (boundbox.min.y + boundbox.max.y) / 2
                        position.z = (boundbox.min.z + boundbox.max.z) / 2

                        console.log('boin', position)
                        resolve({ isInter: false, pos: position, boundBox: boundbox })
                    }
                })

            }
            Model.prototype.buildRoomItemPos = function (halfSize, type = '', boundbox) {
                return new Promise(resolve => {
                    let val = { x: [], y: [] }
                    let cor = B3D.model.floorplan.getRooms()[0].interiorCorners
                    cor.forEach(res => {
                        Object.keys(res).forEach(key => {
                            val[key].push(res[key])
                        })
                    })
                    console.log('ar', val, Math.min(...val.x))

                    var center = B3D.model.floorplan.getCenter();
                    let min = new THREE.Vector3(center.x - halfSize.x, 0, center.z - halfSize.z)
                    let max = new THREE.Vector3(center.x + halfSize.x, 2 * (halfSize.y), center.z + halfSize.z)
                    let boundBoxc = new THREE.Box3(min, max)
                    if (type == '') {

                        this.checkforvalidPos(boundBoxc).then(box => {
                            if (!box.isInter)
                                resolve(box)
                            else
                                this.buildRoomItemPos(halfSize, 'x++', box.boundBox).then(data => resolve(data))
                        })
                    }
                    else {
                        if (type == 'x++' || type == 'z++' || type == 'z--') {
                            if (type == 'x++') {
                                let min = boundbox.max.x + 0.05;
                                let max = min + 2 * halfSize.x - 0.05;
                                boundbox.min.x = min;
                                boundbox.max.x = max;
                            }

                            if (type == 'z++') {
                                let max = boundBoxc.max.z + 0.05;
                                let min = max + 2 * halfSize.z
                                boundBoxc.min.z = max;
                                boundBoxc.max.z = min;

                            }
                            if (type == 'z--') {
                                let min = boundBoxc.min.z - 0.05;
                                let max = min - 2 * halfSize.z
                                boundBoxc.min.z = max;
                                boundBoxc.max.z = min;
                            }

                            type = 'x++'
                        }
                        else if (type == 'x--') {
                            let max = boundbox.min.x - 0.05;
                            let min = max - 2 * halfSize.x + 0.05;

                            boundbox.min.x = min;
                            boundbox.max.x = max;
                        }

                        this.checkforvalidPos(boundbox).then(box => {
                            console.log('box2', box)
                            if (!box.isInter) {
                                //normal inc with x
                                if (box.boundBox.max.x < Math.max(...val.x) && (type == 'x++'))
                                    resolve(box)
                                //normal dec with x
                                if (box.boundBox.min.x > Math.min(...val.x) && (type == 'x--'))
                                    resolve(box)
                                if (box.boundBox.max.x > Math.max(...val.x) && (type == 'x++')) {
                                    if (box.boundBox.min.z > boundBoxc.max.z) {
                                        let max = boundBoxc.max.z + 0.05;
                                        let min = max + 2 * halfSize.z
                                        boundBoxc.min.z = max;
                                        boundBoxc.max.z = min;
                                    }
                                    else if (box.boundBox.max.z < boundBoxc.min.z) {
                                        let min = boundBoxc.min.z - 0.05;
                                        let max = min - 2 * halfSize.z
                                        boundBoxc.min.z = max;
                                        boundBoxc.max.z = min;
                                    }
                                    this.buildRoomItemPos(halfSize, 'x--', boundBoxc).then(data => resolve(data))

                                }

                                if (box.boundBox.min.x < Math.min(...val.x) && type == 'x--') {
                                    if (box.boundBox.min.z <= boundBoxc.max.z && box.boundBox.max.z > boundBoxc.min.z) {
                                        let max = boundBoxc.max.z + 0.05;
                                        let min = max + 2 * halfSize.z
                                        boundBoxc.min.z = max;
                                        boundBoxc.max.z = min;
                                        this.buildRoomItemPos(halfSize, 'z++', boundBoxc).then(data => { resolve(data) })
                                    }
                                    else if (box.boundBox.min.z > boundBoxc.max.z && box.boundBox.max.z > boundBoxc.min.z) {
                                        let min = boundBoxc.min.z - 0.05;
                                        let max = min - 2 * halfSize.z
                                        boundBoxc.min.z = max;
                                        boundBoxc.max.z = min;
                                        this.buildRoomItemPos(halfSize, 'z++', boundBoxc).then(data => { resolve(data) })
                                    }
                                    else if (box.boundBox.max.z < boundBoxc.min.z && box.boundBox.min.x < Math.min(...val.x))
                                        resolve({ isInter: true })
                                }
                            }
                            else {
                                if (type == 'x++')
                                    this.buildRoomItemPos(halfSize, 'x++', box.boundBox).then(data => resolve(data))
                                else
                                    this.buildRoomItemPos(halfSize, 'x--', box.boundBox).then(data => resolve(data))
                            }


                        })
                    }

                })
            }
            Model.prototype.buildPosition = function (callPos, metadata, BoundBox) {
                return new Promise(resolve => {
                    // console.log('data12', callPos, BoundBox)
                    var wallHt = 270;
                    var selectedwall = JSON.parse($('#change-wall').val())
                    let incType = 'z'
                    if (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) + 10 > selectedwall.total_space && (Math.abs(selectedwall.interiorStart.x - selectedwall.interiorEnd.x) - 10 < selectedwall.total_space))
                        incType = 'x'
                    if (callPos.x != 0 && callPos.y != 0 && callPos.z != 0) {
                        if (incType == 'x') {
                            let boundMin = BoundBox.max.x + 0.05;
                            let boundMax = (boundMin + 2 * metadata.halfsize.x) - 0.05;
                            // console.log('selectedwall.interiorEnd.x1', selectedwall.interiorEnd.x, selectedwall.interiorStart.x)
                            if ((selectedwall.interiorEnd.x - selectedwall.interiorStart.x) < 0) {
                                console.log('tyt')
                                boundMin = ((BoundBox.min.x) - 2 * metadata.halfsize.x) + 0.05
                                boundMax = BoundBox.min.x - 0.05;
                            }
                            BoundBox.min.x = boundMin;
                            BoundBox.max.x = boundMax;

                        }
                        else if (incType == 'z') {
                            console.log('BoundBoxes', BoundBox)
                            let boundMin = ((BoundBox.min.z) - 2 * metadata.halfsize.x) + 0.05
                            let boundMax = BoundBox.min.z - 0.05;
                            if ((selectedwall.interiorEnd.y - selectedwall.interiorStart.y) > 0) {
                                boundMin = BoundBox.max.z + 0.05;
                                boundMax = ((BoundBox.max.z) + 2 * metadata.halfsize.x) - 0.05
                            }
                            BoundBox.min.z = boundMin;
                            BoundBox.max.z = boundMax;
                            console.log('BoundBoxes1', BoundBox)
                        }



                        if (metadata.group == 'Wall Cabinets' || metadata.itemType == 7 || metadata.itemName == 3) {
                            wallHt = selectedwall.top - (selectedwall.top * 0.25)
                            if (metadata.group == 'Openings' &&  metadata.itemName == 3)
                                wallHt = selectedwall.top / 2
                            if (metadata.group == 'Openings' && metadata.itemType == 7 )
                                 wallHt = metadata.halfsize.y
                            BoundBox.min.y = wallHt - metadata.halfsize.y;
                            BoundBox.max.y = wallHt + metadata.halfsize.y
                        }
                        this.checkforvalidPos(BoundBox).then(data => {
                            if (data.isInter == false) {
                                if (metadata.group == 'Wall Cabinets')
                                    data.pos.y = selectedwall.top - (selectedwall.top * 0.25)
                                else if (metadata.group == 'Openings' && metadata.itemName == 'Window')
                                    data.pos.y = selectedwall.top / 2
                                resolve({ isInter: data.isInter, pos: data.pos, box: data.boundBox })
                            }
                            else
                                this.buildPosition(data.pos, metadata, data.boundBox).then(data => resolve(data))
                        })
                    }
                    else {
                        wallHt = selectedwall.top - (selectedwall.top * 0.25)
                        let min;
                        let max;
                        let boundBox
                        let POS = {};
                        POS.x = selectedwall.interiorStart.x
                        POS.y = 0;
                        POS.z = Math.round(selectedwall.interiorStart.y)
                        if (metadata.group == 'Wall Cabinets')
                            POS.y = wallHt
                        else if (metadata.group == 'Openings' && metadata.itemName == 'Window')
                            POS.y = selectedwall.top / 2


                        if (incType == 'x') {
                            min = new THREE.Vector3(POS.x, POS.y - (2 * metadata.halfsize.y), POS.z);
                            max = new THREE.Vector3(POS.x + (2 * metadata.halfsize.x), POS.y + (2 * metadata.halfsize.y), POS.z + (2 * metadata.halfsize.z));
                            // boundBox = new THREE.Box3(min, max);
                            console.log('selectedwall.interiorEnd.x', selectedwall.interiorEnd.x, selectedwall.interiorStart.x)
                            if ((selectedwall.interiorEnd.x - selectedwall.interiorStart.x) < 0) {
                                console.log('POS23***', POS, incType)
                                max = new THREE.Vector3(POS.x, POS.y + (2 * metadata.halfsize.y), POS.z);
                                min = new THREE.Vector3(POS.x - (2 * metadata.halfsize.x), POS.y - (2 * metadata.halfsize.y), POS.z - (2 * metadata.halfsize.z));

                            }

                        }
                        else if (incType == 'z') {
                            min = new THREE.Vector3(POS.x, POS.y - (2 * metadata.halfsize.y), POS.z - (2 * metadata.halfsize.z));
                            max = new THREE.Vector3(POS.x + (2 * metadata.halfsize.x), POS.y + (2 * metadata.halfsize.y), POS.z);
                            if ((selectedwall.interiorEnd.y - selectedwall.interiorStart.y) > 0) {
                                min = new THREE.Vector3(POS.x - (2 * metadata.halfsize.x), POS.y - (2 * metadata.halfsize.y), POS.z);
                                max = new THREE.Vector3(POS.x, POS.y + (2 * metadata.halfsize.y), POS.z + (2 * metadata.halfsize.z));
                            }
                            // boundBox = new THREE.Box3(min, max);
                            console.log('z', boundBox)
                        }
                        if (min.y < 0)
                            min.y = 0
                        boundBox = new THREE.Box3(min, max);
                        this.checkforvalidPos(boundBox).then(data => {
                            console.log('data bound', data)
                            if (data.isInter == false)
                                resolve({ isInter: data.isInter, pos: data.pos, box: data.boundBox })

                            else
                                this.buildPosition(data.pos, metadata, data.boundBox).then(data => resolve(data));
                        });


                    }
                })


            }
            Model.prototype.exportSerialized = function () {
                var groupName = []
                // console.log('hi')
                var items_arr = [];
                var grouped_items = {};
                let subcat = {}
                var objects = this.scene.getItems();
                // console.log('loaditems', objects)
                for (var i = 0; i < objects.length; i++) {
                    var object = objects[i];
                    let grp_name = object.metadata.group;
                    let def_sizes = { width: 2 * (object.halfSize.x), height: 2 * (object.halfSize.y), depth: 2 * (object.halfSize.z) }
                    items_arr[i] = {
                        item_name: object.metadata.itemName,
                        item_type: object.metadata.itemType,
                        model_url: object.metadata.modelUrl,
                        def_sizes: def_sizes,
                        image: object.metadata.imageUrl,
                        price: object.metadata.price,
                        sku: ("" + object.metadata.sku).trim(),
                        measurements: object.metadata.measurements,
                        xpos: object.position.x,
                        ypos: object.position.y,
                        zpos: object.position.z,
                        rotation: object.rotation.y,
                        scale_x: object.scale.x,
                        scale_y: object.scale.y,
                        scale_z: object.scale.z,
                        resizable: object.metadata.resizable,
                        fixed: object.fixed,
                        textureUrl: object.textureUrl,
                        textureName: object.textureName
                    };
                    if (!grouped_items[grp_name])
                        grouped_items[grp_name] = [];
                    grouped_items[grp_name].push(items_arr[i]);


                }
                let finalItem = []
                Object.keys(grouped_items).forEach(key => {
                    let subItems = {
                        name: key,
                        items: grouped_items[key]
                    }
                    finalItem.push(subItems);
                })

                var room = {
                    floorplan: (this.floorplan.saveFloorplan()),
                    items: finalItem
                };

                // console.log('romm',room)
                return JSON.stringify(room);
            };

            Model.prototype.newRoom = function (floorplan, items) {
                var _this = this;
                this.scene.clearItems();
                this.floorplan.loadFloorplan(floorplan);
                items.forEach(function (item_group) {
                    item_group.items.forEach(function (item) {
                        //                        console.log('items', item)
                        var position = new THREE.Vector3(item.xpos, item.ypos, item.zpos);
                        var metadata = {
                            itemName: item.item_name,
                            resizable: item.resizable,
                            itemType: item.item_type,
                            imageUrl: item.image,
                            def_sizes: item.def_sizes,
                            measurements: item.measurements,
                            modelUrl: item.model_url,
                            sku: item.sku,
                            group: item_group.name,
                            price: item.price,
                            resizable: item.resizable,
                            uom: item.uom,
                            is_default: true
                        };
                        var textureInfo = { textureUrl: item.textureUrl != undefined ? item.textureUrl : '', textureName: item.textureName != undefined ? item.textureName : '' }
                        var scale = new THREE.Vector3(item.scale_x, item.scale_y, item.scale_z);
                        if (metadata.def_sizes.hasOwnProperty('width'))
                            _this.scene.addItem(item.item_type, item.model_url, metadata, textureInfo, position, item.rotation);
                        else
                            _this.scene.addItem(item.item_type, item.model_url, metadata, textureInfo, position, item.rotation, scale, item.fixed, item.show_measure);
                    });
                });
            };
            return Model;
        })();
        Model_1.Model = Model;
    })(Model = BP3D.Model || (BP3D.Model = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Floorplanner;
    (function (Floorplanner) {
        Floorplanner.floorplannerModes = {
            MOVE: 0,
            DRAW: 1,
            DELETE: 2
        };
        var gridSpacing = 20;
        var gridWidth = 1;
        var gridColor = "#f1f1f1";
        var roomColor = "#f9f9f9";
        var wallWidth = 5;
        var wallWidthHover = 7;
        var wallColor = "#dddddd";
        var wallColorHover = "#008cba";
        var edgeColor = "#888888";
        var edgeColorHover = "#008cba";
        var edgeWidth = 1;
        var deleteColor = "#ff0000";
        var cornerRadius = 0;
        var cornerRadiusHover = 7;
        var cornerColor = "#cccccc";
        var cornerColorHover = "#008cba";
        var FloorplannerView = (function () {
            function FloorplannerView(floorplan, viewmodel, canvas) {
                this.floorplan = floorplan;
                this.viewmodel = viewmodel;
                this.canvas = canvas;
                this.canvasElement = document.getElementById(canvas);
                this.context = this.canvasElement.getContext('2d');
                var scope = this;
                $(window).resize(function () {
                    scope.handleWindowResize();
                });
                this.handleWindowResize();
            }
            FloorplannerView.prototype.handleWindowResize = function () {
                var canvasSel = $("#" + this.canvas);
                var parent = canvasSel.parent();
                canvasSel.height(parent.innerHeight());
                canvasSel.width(parent.innerWidth());
                this.canvasElement.height = parent.innerHeight();
                this.canvasElement.width = parent.innerWidth();
                this.draw();
            };
            FloorplannerView.prototype.draw = function () {
                var _this = this;
                this.context.clearRect(0, 0, this.canvasElement.width, this.canvasElement.height);
                this.drawGrid();
                this.floorplan.getRooms().forEach(function (room) {
                    _this.drawRoom(room);
                });
                this.floorplan.getWalls().forEach(function (wall) {
                    _this.drawWall(wall);
                    //                    console.log("wall:...",wall)
                });
                this.floorplan.getCorners().forEach(function (corner) {
                    _this.drawCorner(corner);
                });
                if (this.viewmodel.mode == Floorplanner.floorplannerModes.DRAW) {
                    this.drawTarget(this.viewmodel.targetX, this.viewmodel.targetY, this.viewmodel.lastNode);
                }
                this.floorplan.getWalls().forEach(function (wall) {
                    _this.drawWallLabels(wall);
                });
            };
            FloorplannerView.prototype.drawWallLabels = function (wall) {
                if (wall.backEdge && wall.frontEdge) {
                    if (wall.backEdge.interiorDistance < wall.frontEdge.interiorDistance) {
                        this.drawEdgeLabel(wall.backEdge);
                    } else {
                        this.drawEdgeLabel(wall.frontEdge);
                    }
                } else if (wall.backEdge) {
                    this.drawEdgeLabel(wall.backEdge);
                } else if (wall.frontEdge) {
                    this.drawEdgeLabel(wall.frontEdge);
                }
            };
            FloorplannerView.prototype.drawWall = function (wall) {
                var hover = (wall === this.viewmodel.activeWall);
                var color = wallColor;
                if (hover && this.viewmodel.mode == Floorplanner.floorplannerModes.DELETE) {
                    color = deleteColor;
                } else if (hover) {
                    color = wallColorHover;
                }
                this.drawLine(this.viewmodel.convertX(wall.getStartX()), this.viewmodel.convertY(wall.getStartY()), this.viewmodel.convertX(wall.getEndX()), this.viewmodel.convertY(wall.getEndY()), hover ? wallWidthHover : wallWidth, color);
                if (!hover && wall.frontEdge) {
                    this.drawEdge(wall.frontEdge, hover);
                }
                if (!hover && wall.backEdge) {
                    this.drawEdge(wall.backEdge, hover);
                }
            };
            FloorplannerView.prototype.drawEdgeLabel = function (edge) {
                var pos = edge.interiorCenter();
                var length = edge.interiorDistance();
                if (length < 60) {
                    return;
                }
                this.context.font = "normal 12px Arial";
                this.context.fillStyle = "#000000";
                this.context.textBaseline = "middle";
                this.context.textAlign = "center";
                this.context.strokeStyle = "#ffffff";
                this.context.lineWidth = 4;
                this.context.strokeText(BP3D.Core.Dimensioning.cmToMeasure(length), this.viewmodel.convertX(pos.x), this.viewmodel.convertY(pos.y));
                this.context.fillText(BP3D.Core.Dimensioning.cmToMeasure(length), this.viewmodel.convertX(pos.x), this.viewmodel.convertY(pos.y));
            };
            FloorplannerView.prototype.drawEdge = function (edge, hover) {
                var color = edgeColor;
                if (hover && this.viewmodel.mode == Floorplanner.floorplannerModes.DELETE) {
                    color = deleteColor;
                } else if (hover) {
                    color = edgeColorHover;
                }
                var corners = edge.corners();
                var scope = this;
            };
            FloorplannerView.prototype.drawRoom = function (room) {
                var scope = this;
                this.drawPolygon(BP3D.Core.Utils.map(room.corners, function (corner) {
                    return scope.viewmodel.convertX(corner.x);
                }), BP3D.Core.Utils.map(room.corners, function (corner) {
                    return scope.viewmodel.convertY(corner.y);
                }), true, roomColor);
            };
            FloorplannerView.prototype.drawCorner = function (corner) {
                var hover = (corner === this.viewmodel.activeCorner);
                var color = cornerColor;
                if (hover && this.viewmodel.mode == Floorplanner.floorplannerModes.DELETE) {
                    color = deleteColor;
                } else if (hover) {
                    color = cornerColorHover;
                }
                this.drawCircle(this.viewmodel.convertX(corner.x), this.viewmodel.convertY(corner.y), hover ? cornerRadiusHover : cornerRadius, color);
            };
            FloorplannerView.prototype.drawTarget = function (x, y, lastNode) {
                this.drawCircle(this.viewmodel.convertX(x), this.viewmodel.convertY(y), cornerRadiusHover, cornerColorHover);
                if (this.viewmodel.lastNode) {
                    this.drawLine(this.viewmodel.convertX(lastNode.x), this.viewmodel.convertY(lastNode.y), this.viewmodel.convertX(x), this.viewmodel.convertY(y), wallWidthHover, wallColorHover);
                }
            };
            FloorplannerView.prototype.drawLine = function (startX, startY, endX, endY, width, color) {
                this.context.beginPath();
                this.context.moveTo(startX, startY);
                this.context.lineTo(endX, endY);
                this.context.lineWidth = width;
                this.context.strokeStyle = color;
                this.context.stroke();
            };
            FloorplannerView.prototype.drawPolygon = function (xArr, yArr, fill, fillColor, stroke, strokeColor, strokeWidth) {
                fill = fill || false;
                stroke = stroke || false;
                this.context.beginPath();
                this.context.moveTo(xArr[0], yArr[0]);
                for (var i = 1; i < xArr.length; i++) {
                    this.context.lineTo(xArr[i], yArr[i]);
                }
                this.context.closePath();
                if (fill) {
                    this.context.fillStyle = fillColor;
                    this.context.fill();
                }
                if (stroke) {
                    this.context.lineWidth = strokeWidth;
                    this.context.strokeStyle = strokeColor;
                    this.context.stroke();
                }
            };
            FloorplannerView.prototype.drawCircle = function (centerX, centerY, radius, fillColor) {
                this.context.beginPath();
                this.context.arc(centerX, centerY, radius, 0, 2 * Math.PI, false);
                this.context.fillStyle = fillColor;
                this.context.fill();
            };
            FloorplannerView.prototype.calculateGridOffset = function (n) {
                if (n >= 0) {
                    return (n + gridSpacing / 2.0) % gridSpacing - gridSpacing / 2.0;
                } else {
                    return (n - gridSpacing / 2.0) % gridSpacing + gridSpacing / 2.0;
                }
            };
            FloorplannerView.prototype.drawGrid = function () {
                var offsetX = this.calculateGridOffset(-this.viewmodel.originX);
                var offsetY = this.calculateGridOffset(-this.viewmodel.originY);
                var width = this.canvasElement.width;
                var height = this.canvasElement.height;
                for (var x = 0; x <= (width / gridSpacing); x++) {
                    this.drawLine(gridSpacing * x + offsetX, 0, gridSpacing * x + offsetX, height, gridWidth, gridColor);
                }
                for (var y = 0; y <= (height / gridSpacing); y++) {
                    this.drawLine(0, gridSpacing * y + offsetY, width, gridSpacing * y + offsetY, gridWidth, gridColor);
                }
            };
            return FloorplannerView;
        })();
        Floorplanner.FloorplannerView = FloorplannerView;
    })(Floorplanner = BP3D.Floorplanner || (BP3D.Floorplanner = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Floorplanner;
    (function (Floorplanner_1) {
        var snapTolerance = 25;
        var Floorplanner = (function () {
            function Floorplanner(canvas, floorplan) {
                this.floorplan = floorplan;
                this.mode = 0;
                this.activeWall = null;
                this.activeCorner = null;
                this.originX = 0;
                this.originY = 0;
                this.targetX = 0;
                this.targetY = 0;
                this.lastNode = null;
                this.modeResetCallbacks = $.Callbacks();
                this.mouseDown = false;
                this.mouseMoved = false;
                this.mouseX = 0;
                this.mouseY = 0;
                this.rawMouseX = 0;
                this.rawMouseY = 0;
                this.lastX = 0;
                this.lastY = 0;
                this.canvasElement = $("#" + canvas);
                this.view = new Floorplanner_1.FloorplannerView(this.floorplan, this, canvas);
                var cmPerFoot = 30.48;
                var pixelsPerFoot = 15.0;

                this.cmPerPixel = cmPerFoot * (1.0 / pixelsPerFoot);
                this.pixelsPerCm = 1.0 / this.cmPerPixel;
                this.wallWidth = 10.0 * this.pixelsPerCm;
                this.setMode(Floorplanner_1.floorplannerModes.MOVE);
                var scope = this;
                this.canvasElement.mousedown(function () {
                    scope.mousedown();
                });
                this.canvasElement.mousemove(function (event) {
                    scope.mousemove(event);
                });
                this.canvasElement.mouseup(function () {
                    scope.mouseup();
                });

                document.getElementById(canvas).addEventListener('touchmove', function (event) { scope.mousemove(event); });
                document.getElementById(canvas).addEventListener('touchstart', function (event) { scope.mousedown(event); });

                this.canvasElement.mouseleave(function () {
                    scope.mouseleave();
                });
                $(document).keyup(function (e) {
                    if (e.keyCode == 27) {
                        scope.escapeKey();
                    }
                });
                floorplan.roomLoadedCallbacks.add(function () {
                    scope.reset();
                });
            }
            Floorplanner.prototype.escapeKey = function () {
                this.setMode(Floorplanner_1.floorplannerModes.MOVE);
                $('#cust-wall-inp').hide();
            };
            Floorplanner.prototype.updateTarget = function () {
                if (this.mode == Floorplanner_1.floorplannerModes.DRAW && this.lastNode) {
                    if (Math.abs(this.mouseX - this.lastNode.x) < snapTolerance) {
                        this.targetX = this.lastNode.x;
                    } else {
                        this.targetX = this.mouseX;
                    }
                    if (Math.abs(this.mouseY - this.lastNode.y) < snapTolerance) {
                        this.targetY = this.lastNode.y;
                    } else {
                        this.targetY = this.mouseY;
                    }
                } else {
                    this.targetX = this.mouseX;
                    this.targetY = this.mouseY;
                }
                this.view.draw();
            };
            /*			
                        Floorplanner.prototype.mousedown = function () {
                            this.mouseDown = true;
                            this.mouseMoved = false;
                            this.lastX = this.rawMouseX;
                            this.lastY = this.rawMouseY;
                            if (this.mode == Floorplanner_1.floorplannerModes.DELETE) {
                                if (this.activeCorner) {
                                    this.activeCorner.removeAll();
                                } else if (this.activeWall) {
                                    this.activeWall.remove();
                                } else {
                                    this.setMode(Floorplanner_1.floorplannerModes.MOVE);
                                }
                            }
                        };
                        Floorplanner.prototype.mousemove = function (event) {
                            this.mouseMoved = true;
                            this.rawMouseX = event.clientX;
                            this.rawMouseY = event.clientY;
                            this.mouseX = (event.clientX - this.canvasElement.offset().left) * this.cmPerPixel + this.originX * this.cmPerPixel;
                            this.mouseY = (event.clientY - this.canvasElement.offset().top) * this.cmPerPixel + this.originY * this.cmPerPixel;
                            if (this.mode == Floorplanner_1.floorplannerModes.DRAW || (this.mode == Floorplanner_1.floorplannerModes.MOVE && this.mouseDown)) {
                                this.updateTarget();
                            }
                            if (this.mode != Floorplanner_1.floorplannerModes.DRAW && !this.mouseDown) {
                                var hoverCorner = this.floorplan.overlappedCorner(this.mouseX, this.mouseY);
                                var hoverWall = this.floorplan.overlappedWall(this.mouseX, this.mouseY);
                                var draw = false;
                                if (hoverCorner != this.activeCorner) {
                                    this.activeCorner = hoverCorner;
                                    draw = true;
                                }
                                if (this.activeCorner == null) {
                                    if (hoverWall != this.activeWall) {
                                        this.activeWall = hoverWall;
                                        draw = true;
                                    }
                                } else {
                                    this.activeWall = null;
                                }
                                if (draw) {
                                    this.view.draw();
                                }
                            }
                            if (this.mouseDown && !this.activeCorner && !this.activeWall) {
                                this.originX += (this.lastX - this.rawMouseX);
                                this.originY += (this.lastY - this.rawMouseY);
                                this.lastX = this.rawMouseX;
                                this.lastY = this.rawMouseY;
                                this.view.draw();
                            }
                            if (this.mode == Floorplanner_1.floorplannerModes.MOVE && this.mouseDown) {
                                if (this.activeCorner) {
                                    this.activeCorner.move(this.mouseX, this.mouseY);
                                    this.activeCorner.snapToAxis(snapTolerance);
                                } else if (this.activeWall) {
                                    this.activeWall.relativeMove((this.rawMouseX - this.lastX) * this.cmPerPixel, (this.rawMouseY - this.lastY) * this.cmPerPixel);
                                    this.activeWall.snapToAxis(snapTolerance);
                                    this.lastX = this.rawMouseX;
                                    this.lastY = this.rawMouseY;
                                }
                                this.view.draw();
                            }
                        };
            */

            Floorplanner.prototype.mousedown = function () {
                this.mouseDown = true;
                this.mouseMoved = false;
                this.lastX = this.rawMouseX;
                this.lastY = this.rawMouseY;
                if (event.type == 'touchstart') {
                    this.mouseDown = false; this.mouseMoved = true;
                }
                if (this.mode == Floorplanner_1.floorplannerModes.DELETE) {
                    if (this.activeCorner) { this.activeCorner.removeAll() }
                    else if (this.activeWall) { this.activeWall.remove() }
                    else { this.setMode(Floorplanner_1.floorplannerModes.MOVE) }
                }
            };

            Floorplanner.prototype.mousemove = function (event) {
                this.mouseMoved = true;
                if (event.type == 'touchmove') {
                    this.rawMouseX = event.touches[0].clientX;
                    this.rawMouseY = event.touches[0].clientY;
                    this.mouseX = (event.touches[0].clientX - this.canvasElement.offset().left) * this.cmPerPixel + this.originX * this.cmPerPixel;
                    this.mouseY = (event.touches[0].clientY - this.canvasElement.offset().top) * this.cmPerPixel + this.originY * this.cmPerPixel;
                    this.mouseDown = true; this.mouseMoved = false;
                } else {
                    this.rawMouseX = event.clientX;
                    this.rawMouseY = event.clientY;
                    this.mouseX = (event.clientX - this.canvasElement.offset().left) * this.cmPerPixel + this.originX * this.cmPerPixel;
                    this.mouseY = (event.clientY - this.canvasElement.offset().top) * this.cmPerPixel + this.originY * this.cmPerPixel;
                }
                if (this.mode == Floorplanner_1.floorplannerModes.DRAW || (this.mode == Floorplanner_1.floorplannerModes.MOVE && this.mouseDown)) { this.updateTarget(); }
                if (this.mode != Floorplanner_1.floorplannerModes.DRAW && !this.mouseDown) {
                    var hoverCorner = this.floorplan.overlappedCorner(this.mouseX, this.mouseY);
                    var hoverWall = this.floorplan.overlappedWall(this.mouseX, this.mouseY);
                    var draw = false; if (hoverCorner != this.activeCorner) { this.activeCorner = hoverCorner; draw = true; }
                    if (this.activeCorner == null) {
                        if (hoverWall != this.activeWall) {
                            this.activeWall = hoverWall;
                            draw = true;
                        }
                    } else { this.activeWall = null; }
                    if (draw) { this.view.draw(); }
                }
                if (this.mouseDown && !this.activeCorner && !this.activeWall) {
                    this.originX += (this.lastX - this.rawMouseX);
                    this.originY += (this.lastY - this.rawMouseY);
                    this.lastX = this.rawMouseX;
                    this.lastY = this.rawMouseY;
                    this.view.draw();
                }
                if (this.mode == Floorplanner_1.floorplannerModes.MOVE && this.mouseDown) {
                    if (this.activeCorner) {
                        this.activeCorner.move(this.mouseX, this.mouseY);
                        this.activeCorner.snapToAxis(snapTolerance);
                    } else if (this.activeWall) {
                        this.activeWall.relativeMove((this.rawMouseX - this.lastX) * this.cmPerPixel, (this.rawMouseY - this.lastY) * this.cmPerPixel);
                        this.activeWall.snapToAxis(snapTolerance);
                        this.lastX = this.rawMouseX;
                        this.lastY = this.rawMouseY;
                    }
                    this.view.draw();
                }
            };


            Floorplanner.prototype.mouseup = function () {
                this.mouseDown = false;
                if (this.mode == Floorplanner_1.floorplannerModes.DRAW && !this.mouseMoved) {
                    var corner = this.floorplan.newCorner(this.targetX, this.targetY);
                    var wallht = Number($('#wall-ht select').val());
                    if ($('#wall-ht option:selected').text() == 'Custom Wall') {
                        if (APP_UOM == 'IN')
                            wallht *= 2.54;
                        else if (APP_UOM == 'FT')
                            wallht *= 30.58;
                    }
                    //                    console.log('wallht', wallht)
                    if (this.lastNode != null) {
                        BP3D.Core.Configuration.setValue(BP3D.Core.configWallHeight, wallht);
                        this.floorplan.newWall(this.lastNode, corner);
                    }
                    if (corner.mergeWithIntersected() && this.lastNode != null) {
                        this.setMode(Floorplanner_1.floorplannerModes.MOVE);
                    }
                    this.lastNode = corner;
                }
                var corners = this.floorplan.getCorners();
                //                console.log("var corners", corners)
                if (corners[2].x < 0 || corners[1].x < 0) {
                    let larger = 0;
                    if (corners[2].x < corners[1].x)
                        larger = corners[2].x;
                    else if (corners[2].x > corners[1].x)
                        larger = corners[1].x;
                    else if (corners[2].x == corners[1].x)
                        larger = corners[1].x;

                    this.floorplan.getCorners()[0].x += Math.abs(larger)
                    this.floorplan.getCorners()[1].x += Math.abs(larger)
                    this.floorplan.getCorners()[2].x += Math.abs(larger)
                    this.floorplan.getCorners()[3].x += Math.abs(larger)
                }
                this.resetOrigin();
            };
            Floorplanner.prototype.mouseleave = function () {
                this.mouseDown = false;
            };
            Floorplanner.prototype.reset = function () {
                this.resizeView();
                this.setMode(Floorplanner_1.floorplannerModes.MOVE);
                this.resetOrigin();
                this.view.draw();
            };
            Floorplanner.prototype.resizeView = function () {
                this.view.handleWindowResize();
            };
            Floorplanner.prototype.resizeView1 = function () {
                this.view.draw()
            };
            Floorplanner.prototype.setMode = function (mode) {
                this.lastNode = null;
                this.mode = mode;
                this.modeResetCallbacks.fire(mode);
                this.updateTarget();
            };
            Floorplanner.prototype.resetOrigin = function () {
                var centerX = this.canvasElement.innerWidth() / 2.0;
                var centerY = this.canvasElement.innerHeight() / 2.0;
                var centerFloorplan = this.floorplan.getCenter();
                this.originX = centerFloorplan.x * this.pixelsPerCm - centerX;
                this.originY = centerFloorplan.z * this.pixelsPerCm - centerY;
            };
            Floorplanner.prototype.convertX = function (x) {
                return (x - this.originX * this.cmPerPixel) * this.pixelsPerCm;
            };
            Floorplanner.prototype.convertY = function (y) {
                return (y - this.originY * this.cmPerPixel) * this.pixelsPerCm;
            };
            return Floorplanner;
        })();
        Floorplanner_1.Floorplanner = Floorplanner;
    })(Floorplanner = BP3D.Floorplanner || (BP3D.Floorplanner = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Controller = function (three, model, camera, element, controls, hud) {
            var scope = this;
            this.enabled = true;
            var three = three;
            var model = model;
            var scene = model.scene;
            var element = element;
            var camera = camera;
            var controls = controls;
            var hud = hud;
            var plane;
            var mouse;
            var intersectedObject;
            var mouseoverObject;
            var selectedObject;
            var mouseDown = false;
            var mouseMoved = false;
            var rotateMouseOver = false;
            var states = {
                UNSELECTED: 0,
                SELECTED: 1,
                DRAGGING: 2,
                ROTATING: 3,
                ROTATING_FREE: 4,
                PANNING: 5
            };
            var state = states.UNSELECTED;
            this.needsUpdate = true;
            function init() {
                element.mousedown(mouseDownEvent);
                element.mouseup(mouseUpEvent);
                element.mousemove(mouseMoveEvent);
                document.querySelector('#viewer').addEventListener("touchstart", mouseDownEvent);
                document.querySelector('#viewer').addEventListener("touchend", mouseUpEvent);
                document.querySelector('#viewer').addEventListener("touchmove", mouseMoveEvent);
                mouse = new THREE.Vector2();
                scene.itemRemovedCallbacks.add(itemRemoved);
                scene.itemLoadedCallbacks.add(itemLoaded);
                setGroundPlane();
            }
            function itemLoaded(item) {
                if (!item.position_set) {
                    scope.setSelectedObject(item);
                    switchState(states.DRAGGING);
                    var pos = item.position.clone();
                    pos.y = 0;
                    var vec = three.projectVector(pos);
                    clickPressed(vec);
                }
                item.position_set = true;
                if(item.metadata.group!='Appliances')
                item.boundMove(item.position)
                scope.setSelectedObject(item);
                switchState(states.SELECTED);
            }
            function clickPressed(vec2) {
                vec2 = vec2 || mouse;
                var intersection = scope.itemIntersection(mouse, selectedObject);
                if (intersection) {
                    selectedObject.clickPressed(intersection);
                }
            }
            function clickDragged(vec2) {
                vec2 = vec2 || mouse;
                var intersection = scope.itemIntersection(mouse, selectedObject);
                // console.log('interset',intersection,)
                if (intersection) {
                    if (scope.isRotating()) {
                        selectedObject.rotate(intersection);
                    } else {
                        selectedObject.clickDragged(intersection);
                    }
                }


            }

            // function clickDragged(vec2) {
            //     vec2 = vec2 || mouse;
            //     var intersection = scope.itemIntersection(mouse, selectedObject);
            //     console.log('interset',selectedObject,)
            //     if (intersection && selectedObject!=null) {
            //         if (scope.isRotating()) {
            //             selectedObject.rotate(intersection);
            //         } else {

            //             selectedObject.clickDragged(intersection);

            //         }
            //     }
            // }
            function itemRemoved(item) {
                if (item === selectedObject) {
                    selectedObject.setUnselected();
                    selectedObject.mouseOff();
                    scope.setSelectedObject(null);
                }
            }
            function setGroundPlane() {
                var size = 10000;
                plane = new THREE.Mesh(new THREE.PlaneGeometry(size, size), new THREE.MeshBasicMaterial());
                plane.rotation.x = -Math.PI / 2;
                plane.visible = false;
                scene.add(plane);
            }
            function checkWallsAndFloors(event) {
                if (state == states.UNSELECTED && mouseoverObject == null) {
                    var wallEdgePlanes = model.floorplan.wallEdgePlanes();
                    var wallIntersects = scope.getIntersections(mouse, wallEdgePlanes, true);
                    if (wallIntersects.length > 0) {
                        var wall = wallIntersects[0].object.edge;
                        three.wallClicked.fire(wall);
                        return;
                    }
                    var floorPlanes = model.floorplan.floorPlanes();
                    var floorIntersects = scope.getIntersections(mouse, floorPlanes, false);
                    if (floorIntersects.length > 0) {
                        var room = floorIntersects[0].object.room;
                        three.floorClicked.fire(room);
                        return;
                    }
                    three.nothingClicked.fire();
                }
            }
            function mouseMoveEvent(event) {
                if (scope.enabled) {
                    event.preventDefault();
                    mouseMoved = true;
                    mouse.x = event.clientX;
                    mouse.y = event.clientY;
                    if (event.type == 'touchmove') {
                        mouse.x = event.touches[0].clientX;
                        mouse.y = event.touches[0].clientY;
                    }
                    if (!mouseDown) {
                        updateIntersections();
                    }
                    switch (state) {
                        case states.UNSELECTED:
                            updateMouseover();
                            break;
                        case states.SELECTED:
                            updateMouseover();
                            break;
                        case states.DRAGGING:
                        case states.ROTATING:
                        case states.ROTATING_FREE:
                            clickDragged();
                            hud.update();
                            scope.needsUpdate = true;
                            break;
                    }
                }
            }
            this.isRotating = function () {
                //                console.log('rotate', state)
                return (state == states.ROTATING || state == states.ROTATING_FREE);
            };
            function mouseDownEvent(event) {
                if (scope.enabled) {
                    event.preventDefault();
                    mouseMoved = false;
                    mouseDown = true;
                    if (event.type == 'touchstart') {
                        mouse.x = event.touches[0].clientX;
                        mouse.y = event.touches[0].clientY;
                        updateIntersections();
                    }
                    switch (state) {
                        case states.SELECTED:
                            if (rotateMouseOver) {
                                switchState(states.ROTATING);
                            } else if (intersectedObject != null) {
                                scope.setSelectedObject(intersectedObject);
                                if (!intersectedObject.fixed) {
                                    switchState(states.DRAGGING);
                                }
                            }
                            break;
                        case states.UNSELECTED:
                            if (intersectedObject != null) {
                                scope.setSelectedObject(intersectedObject);
                                if (!intersectedObject.fixed) {
                                    switchState(states.DRAGGING);
                                }
                            }
                            break;
                        case states.DRAGGING:
                        case states.ROTATING:
                            break;
                        case states.ROTATING_FREE:
                            switchState(states.SELECTED);
                            break;
                    }
                }
            }
            function mouseUpEvent(event) {
                if (scope.enabled) {
                    mouseDown = false;
                    switch (state) {
                        case states.DRAGGING:
                            selectedObject.clickReleased();
                            switchState(states.SELECTED);
                            break;
                        case states.ROTATING:
                            if (!mouseMoved) {
                                switchState(states.ROTATING_FREE);
                            } else {
                                switchState(states.SELECTED);
                            }
                            break;
                        case states.UNSELECTED:
                            if (!mouseMoved) {
                                checkWallsAndFloors();
                            }
                            break;
                        case states.SELECTED:
                            if (intersectedObject == null && !mouseMoved) {
                                switchState(states.UNSELECTED);
                                checkWallsAndFloors();
                            }
                            break;
                        case states.ROTATING_FREE:
                            break;
                    }
                }
            }
            function switchState(newState) {
                if (newState != state) {
                    onExit(state);
                    onEntry(newState);
                }
                state = newState;
                hud.setRotating(scope.isRotating());
            }
            function onEntry(state) {
                switch (state) {
                    case states.UNSELECTED:
                        scope.setSelectedObject(null);
                    case states.SELECTED:
                        controls.enabled = true;
                        break;
                    case states.ROTATING:
                    case states.ROTATING_FREE:
                        controls.enabled = false;
                        break;
                    case states.DRAGGING:
                        three.setCursorStyle("move");
                        clickPressed();
                        controls.enabled = false;
                        break;
                }
            }
            function onExit(state) {
                switch (state) {
                    case states.UNSELECTED:
                    case states.SELECTED:
                        break;
                    case states.DRAGGING:
                        if (mouseoverObject) {
                            three.setCursorStyle("pointer");
                        } else {
                            three.setCursorStyle("auto");
                        }
                        break;
                    case states.ROTATING:
                    case states.ROTATING_FREE:
                        break;
                }
            }
            this.selectedObject = function () {
                return selectedObject;
            };
            function updateIntersections() {
                var hudObject = hud.getObject();
                if (hudObject != null) {
                    var hudIntersects = scope.getIntersections(mouse, hudObject, false, false, true);
                    if (hudIntersects.length > 0) {
                        rotateMouseOver = true;
                        hud.setMouseover(true);
                        intersectedObject = null;
                        return;
                    }
                }
                rotateMouseOver = false;
                hud.setMouseover(false);
                var items = model.scene.getItems();
                var intersects = scope.getIntersections(mouse, items, false, true);
                if (intersects.length > 0) {
                    intersectedObject = intersects[0].object;
                } else {
                    intersectedObject = null;
                }
            }
            function normalizeVector2(vec2) {
                var retVec = new THREE.Vector2();
                retVec.x = ((vec2.x - three.widthMargin) / (window.innerWidth - three.widthMargin)) * 2 - 1;
                retVec.y = - ((vec2.y - three.heightMargin) / (window.innerHeight - three.heightMargin)) * 2 + 1;
                return retVec;
            }
            function mouseToVec3(vec2) {
                var normVec2 = normalizeVector2(vec2);
                var vector = new THREE.Vector3(normVec2.x, normVec2.y, 0.5);
                vector.unproject(camera);
                return vector;
            }
            this.itemIntersection = function (vec2, item) {
                var customIntersections = [];
                // if( item.length)
                customIntersections = item.customIntersectionPlanes();
                //    console.log('item', customIntersections)
                if (item.metadata.itemName == 'BCCLR' && scope.isRotating())
                    customIntersections = [];
                var intersections = null;
                if (customIntersections && customIntersections.length > 0) {
                    intersections = this.getIntersections(vec2, customIntersections, true);
                } else {
                    intersections = this.getIntersections(vec2, plane);
                }
                if (intersections.length > 0) {
                    return intersections[0];
                } else {
                    return null;
                }
            };

            // this.itemIntersection = function (vec2, item) {
            //     console.log("intersection",item)

            //     var customIntersections = [];
            //     // if( item.length)
            //     if(item!=null)
            //     customIntersections = item.customIntersectionPlanes();
            //     //             console.log('item', scope.STATE)
            //     if (item!=null && item.metadata.itemName == 'BCCLR' && scope.isRotating())
            //         customIntersections = [];
            //     var intersections = null;
            //     if (customIntersections && customIntersections.length > 0) {
            //         intersections = this.getIntersections(vec2, customIntersections, true);
            //     } else {
            //         intersections = this.getIntersections(vec2, plane);
            //     }
            //     if (intersections.length > 0) {
            //         return intersections[0];
            //     } else {
            //         return null;
            //     }
            // };
            this.getIntersections = function (vec2, objects, filterByNormals, onlyVisible, recursive, linePrecision) {
                var vector = mouseToVec3(vec2);
                onlyVisible = onlyVisible || false;
                filterByNormals = filterByNormals || false;
                recursive = recursive || false;
                linePrecision = linePrecision || 20;

                var direction = vector.sub(camera.position).normalize();
                //                console.log("direction:", camera.position, camera.direction)
                var raycaster = new THREE.Raycaster(camera.position, direction);
                raycaster.linePrecision = linePrecision;
                var intersections;
                if (objects instanceof Array) {
                    intersections = raycaster.intersectObjects(objects, recursive);
                } else {
                    intersections = raycaster.intersectObject(objects, recursive);
                }
                if (onlyVisible) {
                    intersections = BP3D.Core.Utils.removeIf(intersections, function (intersection) {
                        return !intersection.object.visible;
                    });
                }
                if (filterByNormals) {
                    intersections = BP3D.Core.Utils.removeIf(intersections, function (intersection) {
                        var dot = intersection.face.normal.dot(direction);
                        return (dot > 0);
                    });
                }
                return intersections;
            };
            this.setSelectedObject = function (object) {
                if (state === states.UNSELECTED) {
                    switchState(states.SELECTED);
                }
                if (selectedObject != null) {
                    selectedObject.setUnselected();
                }
                if (object != null) {
                    selectedObject = object;
                    selectedObject.setSelected();
                    three.itemSelectedCallbacks.fire(object);
                } else {
                    selectedObject = null;
                    three.itemUnselectedCallbacks.fire();
                }
                this.needsUpdate = true;
            };
            function updateMouseover() {
                if (intersectedObject != null) {
                    if (mouseoverObject != null) {
                        if (mouseoverObject !== intersectedObject) {
                            mouseoverObject.mouseOff();
                            mouseoverObject = intersectedObject;
                            mouseoverObject.mouseOver();
                            scope.needsUpdate = true;
                        } else { }
                    } else {
                        mouseoverObject = intersectedObject;
                        mouseoverObject.mouseOver();
                        three.setCursorStyle("pointer");
                        scope.needsUpdate = true;
                    }
                } else if (mouseoverObject != null) {
                    mouseoverObject.mouseOff();
                    three.setCursorStyle("auto");
                    mouseoverObject = null;
                    scope.needsUpdate = true;
                }
            }
            init();
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Floor = function (scene, room) {
            var scope = this;
            this.room = room;
            var scene = scene;
            var floorPlane = null;
            var roofPlane = null;
            init();
            function init() {
                scope.room.fireOnFloorChange(redraw);
                floorPlane = buildFloor();
            }
            function redraw() {
                scope.removeFromScene();
                floorPlane = buildFloor();
                scope.addToScene();
            }
            function buildFloor() {
                var textureSettings = scope.room.getTexture();
                var floorTexture = THREE.ImageUtils.loadTexture(textureSettings.url);
                floorTexture.wrapS = THREE.RepeatWrapping;
                floorTexture.wrapT = THREE.RepeatWrapping;
                floorTexture.repeat.set(1, 1);
                var floorMaterialTop = new THREE.MeshPhongMaterial({
                    map: floorTexture,
                    side: THREE.DoubleSide,
                    color: 0xcccccc,
                    specular: 0x0a0a0a
                });
                var textureScale = textureSettings.scale;
                var points = [];
                scope.room.interiorCorners.forEach(function (corner) {
                    points.push(new THREE.Vector2(corner.x / textureScale, corner.y / textureScale));
                });
                var shape = new THREE.Shape(points);
                var geometry = new THREE.ShapeGeometry(shape);
                var floor = new THREE.Mesh(geometry, floorMaterialTop);
                floor.rotation.set(Math.PI / 2, 0, 0);
                floor.scale.set(textureScale, textureScale, textureScale);
                floor.receiveShadow = true;
                floor.castShadow = false;
                return floor;
            }
            function buildRoof() {
                var roofMaterial = new THREE.MeshBasicMaterial({
                    side: THREE.FrontSide,
                    color: 0xe5e5e5
                });
                var points = [];
                scope.room.interiorCorners.forEach(function (corner) {
                    points.push(new THREE.Vector2(corner.x, corner.y));
                });
                var shape = new THREE.Shape(points);
                var geometry = new THREE.ShapeGeometry(shape);
                var roof = new THREE.Mesh(geometry, roofMaterial);
                roof.rotation.set(Math.PI / 2, 0, 0);
                roof.position.y = 250;
                return roof;
            }
            this.addToScene = function () {
                scene.add(floorPlane);
                scene.add(room.floorPlane);
            };
            this.removeFromScene = function () {
                scene.remove(floorPlane);
                scene.remove(room.floorPlane);
            };
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Edge = function (scene, edge, controls) {
            var scope = this;
            var scene = scene;
            var edge = edge;
            var controls = controls;
            var wall = edge.wall;
            var front = edge.front;
            var planes = [];
            var basePlanes = [];
            var texture = null;
            var lightMap = THREE.ImageUtils.loadTexture("images/rooms/textures/walllightmap.png");
            var fillerColor = 0xdddddd;
            var sideColor = 0xcccccc;
            var baseColor = 0xdddddd;
            this.visible = false;
            this.remove = function () {
                edge.redrawCallbacks.remove(redraw);
                controls.cameraMovedCallbacks.remove(updateVisibility);
                removeFromScene();
            };
            function init() {
                edge.redrawCallbacks.add(redraw);
                controls.cameraMovedCallbacks.add(updateVisibility);
                updateTexture();
                updatePlanes();
                addToScene();
            }
            function redraw() {
                removeFromScene();
                updateTexture();
                updatePlanes();
                addToScene();
            }
            function removeFromScene() {
                planes.forEach(function (plane) {
                    scene.remove(plane);
                });
                basePlanes.forEach(function (plane) {
                    scene.remove(plane);
                });
                planes = [];
                basePlanes = [];
            }
            function addToScene() {
                planes.forEach(function (plane) {
                    scene.add(plane);
                });
                basePlanes.forEach(function (plane) {
                    scene.add(plane);
                });
                updateVisibility();
            }
            function updateVisibility() {
                var start = edge.interiorStart();
                var end = edge.interiorEnd();
                var x = end.x - start.x;
                var y = end.y - start.y;
                var normal = new THREE.Vector3(-y, 0, x);
                normal.normalize();
                var position = controls.object.position.clone();
                var focus = new THREE.Vector3((start.x + end.x) / 2.0, 0, (start.y + end.y) / 2.0);
                var direction = position.sub(focus).normalize();
                var dot = normal.dot(direction);
                scope.visible = (dot >= 0);
                planes.forEach(function (plane) {
                    plane.visible = scope.visible;
                });
                updateObjectVisibility();
            }
            function updateObjectVisibility() {
                wall.items.forEach(function (item) {
                    item.updateEdgeVisibility(scope.visible, front);
                });
                wall.onItems.forEach(function (item) {
                    item.updateEdgeVisibility(scope.visible, front);
                });
            }
            function updateTexture(callback) {
                callback = callback || function () {
                    scene.needsUpdate = true;
                };
                var textureData = edge.getTexture();
                var stretch = textureData.stretch;
                var url = textureData.url;
                var scale = textureData.scale;
                texture = THREE.ImageUtils.loadTexture(url, null, callback);
                if (!stretch) {
                    var height = wall.height;
                    var width = edge.interiorDistance();
                    texture.wrapT = THREE.RepeatWrapping;
                    texture.wrapS = THREE.RepeatWrapping;
                    texture.repeat.set(width / scale, height / scale);
                    texture.needsUpdate = true;
                }
            }
            function updatePlanes() {
                var wallMaterial = new THREE.MeshBasicMaterial({
                    color: 0xffffff,
                    side: THREE.FrontSide,
                    map: texture,
                });
                var fillerMaterial = new THREE.MeshBasicMaterial({
                    color: fillerColor,
                    side: THREE.DoubleSide
                });
                planes.push(makeWall(edge.exteriorStart(), edge.exteriorEnd(), edge.exteriorTransform, edge.invExteriorTransform, fillerMaterial));
                planes.push(makeWall(edge.interiorStart(), edge.interiorEnd(), edge.interiorTransform, edge.invInteriorTransform, wallMaterial));
                basePlanes.push(buildFiller(edge, 0, THREE.BackSide, baseColor));
                planes.push(buildFiller(edge, wall.height, THREE.DoubleSide, fillerColor));
                planes.push(buildSideFillter(edge.interiorStart(), edge.exteriorStart(), wall.height, sideColor));
                planes.push(buildSideFillter(edge.interiorEnd(), edge.exteriorEnd(), wall.height, sideColor));
            }
            function makeWall(start, end, transform, invTransform, material) {
                var v1 = toVec3(start);
                var v2 = toVec3(end);
                var v3 = v2.clone();
                v3.y = wall.height;
                var v4 = v1.clone();
                v4.y = wall.height;
                var points = [v1.clone(), v2.clone(), v3.clone(), v4.clone()];
                points.forEach(function (p) {
                    p.applyMatrix4(transform);
                });
                var shape = new THREE.Shape([new THREE.Vector2(points[0].x, points[0].y), new THREE.Vector2(points[1].x, points[1].y), new THREE.Vector2(points[2].x, points[2].y), new THREE.Vector2(points[3].x, points[3].y)]);
                wall.items.forEach(function (item) {
                    var pos = item.position.clone();
                    pos.applyMatrix4(transform);
                    var halfSize = item.halfSize;
                    var min = halfSize.clone().multiplyScalar(-1);
                    var max = halfSize.clone();
                    min.add(pos);
                    max.add(pos);
                    var holePoints = [new THREE.Vector2(min.x, min.y), new THREE.Vector2(max.x, min.y), new THREE.Vector2(max.x, max.y), new THREE.Vector2(min.x, max.y)];
                    shape.holes.push(new THREE.Path(holePoints));
                });
                var geometry = new THREE.ShapeGeometry(shape);
                geometry.vertices.forEach(function (v) {
                    v.applyMatrix4(invTransform);
                });
                var totalDistance = BP3D.Core.Utils.distance(v1.x, v1.z, v2.x, v2.z);
                var height = wall.height;
                geometry.faceVertexUvs[0] = [];
                function vertexToUv(vertex) {
                    var x = BP3D.Core.Utils.distance(v1.x, v1.z, vertex.x, vertex.z) / totalDistance;
                    var y = vertex.y / height;
                    return new THREE.Vector2(x, y);
                }
                geometry.faces.forEach(function (face) {
                    var vertA = geometry.vertices[face.a];
                    var vertB = geometry.vertices[face.b];
                    var vertC = geometry.vertices[face.c];
                    geometry.faceVertexUvs[0].push([vertexToUv(vertA), vertexToUv(vertB), vertexToUv(vertC)]);
                });
                geometry.faceVertexUvs[1] = geometry.faceVertexUvs[0];
                geometry.computeFaceNormals();
                geometry.computeVertexNormals();
                var mesh = new THREE.Mesh(geometry, material);
                return mesh;
            }
            function buildSideFillter(p1, p2, height, color) {
                var points = [toVec3(p1), toVec3(p2), toVec3(p2, height), toVec3(p1, height)];
                var geometry = new THREE.Geometry();
                points.forEach(function (p) {
                    geometry.vertices.push(p);
                });
                geometry.faces.push(new THREE.Face3(0, 1, 2));
                geometry.faces.push(new THREE.Face3(0, 2, 3));
                var fillerMaterial = new THREE.MeshBasicMaterial({
                    color: color,
                    side: THREE.DoubleSide
                });
                var filler = new THREE.Mesh(geometry, fillerMaterial);
                return filler;
            }
            function buildFiller(edge, height, side, color) {
                var points = [toVec2(edge.exteriorStart()), toVec2(edge.exteriorEnd()), toVec2(edge.interiorEnd()), toVec2(edge.interiorStart())];
                var fillerMaterial = new THREE.MeshBasicMaterial({
                    color: color,
                    side: side
                });
                var shape = new THREE.Shape(points);
                var geometry = new THREE.ShapeGeometry(shape);
                var filler = new THREE.Mesh(geometry, fillerMaterial);
                filler.rotation.set(Math.PI / 2, 0, 0);
                filler.position.y = height;
                return filler;
            }
            function toVec2(pos) {
                return new THREE.Vector2(pos.x, pos.y);
            }
            function toVec3(pos, height) {
                height = height || 0;
                return new THREE.Vector3(pos.x, height, pos.y);
            }
            init();
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Floorplan = function (scene, floorplan, controls) {
            var scope = this;
            this.scene = scene;
            this.floorplan = floorplan;
            this.controls = controls;
            this.floors = [];
            this.edges = [];
            floorplan.fireOnUpdatedRooms(redraw);
            function redraw() {
                scope.floors.forEach(function (floor) {
                    floor.removeFromScene();
                });
                scope.edges.forEach(function (edge) {
                    edge.remove();
                });
                scope.floors = [];
                scope.edges = [];
                scope.floorplan.getRooms().forEach(function (room) {
                    var threeFloor = new Three.Floor(scene, room);
                    scope.floors.push(threeFloor);
                    threeFloor.addToScene();
                });
                scope.floorplan.wallEdges().forEach(function (edge) {
                    var threeEdge = new Three.Edge(scene, edge, scope.controls);
                    scope.edges.push(threeEdge);
                });
            }
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Lights = function (scene, floorplan) {
            var scope = this;
            var scene = scene;
            var floorplan = floorplan;
            var tol = 1;
            var height = 300;
            // var height =50;

            var dirLight;
            this.getDirLight = function () {
                return dirLight;
            };
            function init() {
                // var light = new THREE.HemisphereLight(0xffffff, 0x888888, 1.1);
                var light = new THREE.HemisphereLight(0xffffff, 0x888888, 1.1);

                light.position.set(height / 2, height * 2, -300);
                scene.add(light);
                dirLight = new THREE.DirectionalLight(0xffffff, 0);
                // dirLight = new THREE.DirectionalLight(0xdddddd, 0);
                // dirLight.color.setHSL(1, 1, 0.1);
                dirLight.color.setHSL(1, 1, 0.1);
                //
                dirLight.castShadow = true;
                dirLight.shadowMapWidth = 1024;
                dirLight.shadowMapHeight = 1024;
                dirLight.shadowCameraFar = height + tol;
                dirLight.shadowBias = -0.0001;
                dirLight.shadowDarkness = 0.1;
                dirLight.visible = true;
                dirLight.shadowCameraVisible = false;
                scene.add(dirLight);
                scene.add(dirLight.target);
                floorplan.fireOnUpdatedRooms(updateShadowCamera);
            }
            function updateShadowCamera() {
                var size = floorplan.getSize();
                var d = (Math.max(size.z, size.x) + tol) / 2.0;
                var center = floorplan.getCenter();
                var pos = new THREE.Vector3(center.x, height, center.z);
                dirLight.position.copy(pos);
                dirLight.target.position.copy(center);
                dirLight.shadowCameraLeft = -d;
                dirLight.shadowCameraRight = d;
                dirLight.shadowCameraTop = d;
                dirLight.shadowCameraBottom = -d;
                if (dirLight.shadowCamera) {
                    dirLight.shadowCamera.left = dirLight.shadowCameraLeft;
                    dirLight.shadowCamera.right = dirLight.shadowCameraRight;
                    dirLight.shadowCamera.top = dirLight.shadowCameraTop;
                    dirLight.shadowCamera.bottom = dirLight.shadowCameraBottom;
                    dirLight.shadowCamera.updateProjectionMatrix();
                }
            }
            init();
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Skybox = function (scene) {
            var scope = this;
            var scene = scene;
            var topColor = 0xffffff;
            var bottomColor = 0xe9e9e9;
            var verticalOffset = 500;
            var sphereRadius = 4000;
            var widthSegments = 32;
            var heightSegments = 15;
            var vertexShader = ["varying vec3 vWorldPosition;", "void main() {", "  vec4 worldPosition = modelMatrix * vec4( position, 1.0 );", "  vWorldPosition = worldPosition.xyz;", "  gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );", "}"].join('\n');
            var fragmentShader = ["uniform vec3 topColor;", "uniform vec3 bottomColor;", "uniform float offset;", "varying vec3 vWorldPosition;", "void main() {", "  float h = normalize( vWorldPosition + offset ).y;", "  gl_FragColor = vec4( mix( bottomColor, topColor, (h + 1.0) / 2.0), 1.0 );", "}"].join('\n');
            function init() {
                var uniforms = {
                    topColor: {
                        type: "c",
                        value: new THREE.Color(topColor)
                    },
                    bottomColor: {
                        type: "c",
                        value: new THREE.Color(bottomColor)
                    },
                    offset: {
                        type: "f",
                        value: verticalOffset
                    }
                };
                var skyGeo = new THREE.SphereGeometry(sphereRadius, widthSegments, heightSegments);
                var skyMat = new THREE.ShaderMaterial({
                    vertexShader: vertexShader,
                    fragmentShader: fragmentShader,
                    uniforms: uniforms,
                    side: THREE.BackSide
                });
                var sky = new THREE.Mesh(skyGeo, skyMat);
                scene.add(sky);
            }
            init();
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Controls = function (object, domElement) {
            this.object = object;
            this.domElement = (domElement !== undefined) ? domElement : document;
            this.enabled = true;
            this.target = new THREE.Vector3();
            this.center = this.target;
            this.noZoom = false;
            this.zoomSpeed = 1.0;
            this.minDistance = 0;
            this.maxDistance = 1500;
            this.noRotate = false;
            this.rotateSpeed = 1.0;
            this.noPan = false;
            this.keyPanSpeed = 40.0;
            this.autoRotate = false;
            this.autoRotateSpeed = 2.0;
            this.minPolarAngle = 0;
            this.maxPolarAngle = Math.PI / 2;
            this.noKeys = false;
            this.keys = {
                LEFT: 37,
                UP: 38,
                RIGHT: 39,
                BOTTOM: 40
            };
            this.cameraMovedCallbacks = $.Callbacks();
            this.needsUpdate = true;
            var scope = this;
            var EPS = 0.000001;
            var rotateStart = new THREE.Vector2();
            var rotateEnd = new THREE.Vector2();
            var rotateDelta = new THREE.Vector2();
            var panStart = new THREE.Vector2();
            var panEnd = new THREE.Vector2();
            var panDelta = new THREE.Vector2();
            var dollyStart = new THREE.Vector2();
            var dollyEnd = new THREE.Vector2();
            var dollyDelta = new THREE.Vector2();
            var phiDelta = 0;
            var thetaDelta = 0;
            var scale = 1;
            var pan = new THREE.Vector3();
            var STATE = {
                NONE: -1,
                ROTATE: 0,
                DOLLY: 1,
                PAN: 2,
                TOUCH_ROTATE: 3,
                TOUCH_DOLLY: 4,
                TOUCH_PAN: 5
            };
            var state = STATE.NONE;
            this.controlsActive = function () {
                return (state === STATE.NONE);
            };
            this.setPan = function (vec3) {
                pan = vec3;
            };
            this.panTo = function (vec3) {
                var newTarget = new THREE.Vector3(vec3.x, scope.target.y, vec3.z);
                var delta = scope.target.clone().sub(newTarget);
                pan.sub(delta);
                scope.update();
            };
            this.rotateLeft = function (angle) {
                if (angle === undefined) {
                    angle = getAutoRotationAngle();
                }
                thetaDelta -= angle;
            };
            this.rotateUp = function (angle) {
                if (angle === undefined) {
                    angle = getAutoRotationAngle();
                }
                phiDelta -= angle;
            };
            this.panLeft = function (distance) {
                var panOffset = new THREE.Vector3();
                var te = this.object.matrix.elements;
                panOffset.set(te[0], 0, te[2]);
                panOffset.normalize();
                panOffset.multiplyScalar(-distance);
                pan.add(panOffset);
            };
            this.panUp = function (distance) {
                var panOffset = new THREE.Vector3();
                var te = this.object.matrix.elements;
                panOffset.set(te[4], 0, te[6]);
                panOffset.normalize();
                panOffset.multiplyScalar(distance);
                pan.add(panOffset);
            };
            this.pan = function (delta) {
                var element = scope.domElement === document ? scope.domElement.body : scope.domElement;
                if (scope.object.fov !== undefined) {
                    var position = scope.object.position;
                    var offset = position.clone().sub(scope.target);
                    var targetDistance = offset.length();
                    targetDistance *= Math.tan((scope.object.fov / 2) * Math.PI / 180.0);
                    scope.panLeft(2 * delta.x * targetDistance / element.clientHeight);
                    scope.panUp(2 * delta.y * targetDistance / element.clientHeight);
                } else if (scope.object.top !== undefined) {
                    scope.panLeft(delta.x * (scope.object.right - scope.object.left) / element.clientWidth);
                    scope.panUp(delta.y * (scope.object.top - scope.object.bottom) / element.clientHeight);
                } else {
                    console.warn('WARNING: OrbitControls.js encountered an unknown camera type - pan disabled.');
                }
                scope.update();
            };
            this.panXY = function (x, y) {
                scope.pan(new THREE.Vector2(x, y));
            };
            this.dollyIn = function (dollyScale) {
                if (dollyScale === undefined) {
                    dollyScale = getZoomScale();
                }
                scale /= dollyScale;
            };
            this.dollyOut = function (dollyScale) {
                if (dollyScale === undefined) {
                    dollyScale = getZoomScale();
                }
                scale *= dollyScale;
            };
            this.update = function () {
                var position = this.object.position;
                var offset = position.clone().sub(this.target);
                var theta = Math.atan2(offset.x, offset.z);
                var phi = Math.atan2(Math.sqrt(offset.x * offset.x + offset.z * offset.z), offset.y);
                if (this.autoRotate) {
                    this.rotateLeft(getAutoRotationAngle());
                }
                theta += thetaDelta;
                phi += phiDelta;
                phi = Math.max(this.minPolarAngle, Math.min(this.maxPolarAngle, phi));
                phi = Math.max(EPS, Math.min(Math.PI - EPS, phi));
                var radius = offset.length() * scale;
                radius = Math.max(this.minDistance, Math.min(this.maxDistance, radius));
                this.target.add(pan);
                offset.x = radius * Math.sin(phi) * Math.sin(theta);
                offset.y = radius * Math.cos(phi);
                offset.z = radius * Math.sin(phi) * Math.cos(theta);
                position.copy(this.target).add(offset);
                this.object.lookAt(this.target);
                thetaDelta = 0;
                phiDelta = 0;
                scale = 1;
                pan.set(0, 0, 0);
                this.cameraMovedCallbacks.fire();
                this.needsUpdate = true;
            };
            function getAutoRotationAngle() {
                return 2 * Math.PI / 60 / 60 * scope.autoRotateSpeed;
            }
            function getZoomScale() {
                return Math.pow(0.95, scope.zoomSpeed);
            }
            function onMouseDown(event) {
                if (scope.enabled === false) {
                    return;
                }
                event.preventDefault();
                if (event.button === 0) {
                    if (scope.noRotate === true) {
                        return;
                    }
                    state = STATE.ROTATE;
                    rotateStart.set(event.clientX, event.clientY);
                } else if (event.button === 1) {
                    if (scope.noZoom === true) {
                        return;
                    }
                    state = STATE.DOLLY;
                    dollyStart.set(event.clientX, event.clientY);
                } else if (event.button === 2) {
                    if (scope.noPan === true) {
                        return;
                    }
                    state = STATE.PAN;
                    panStart.set(event.clientX, event.clientY);
                }
                scope.domElement.addEventListener('mousemove', onMouseMove, false);
                scope.domElement.addEventListener('mouseup', onMouseUp, false);
            }
            function onMouseMove(event) {
                if (scope.enabled === false)
                    return;
                event.preventDefault();
                var element = scope.domElement === document ? scope.domElement.body : scope.domElement;
                if (state === STATE.ROTATE) {
                    if (scope.noRotate === true)
                        return;
                    rotateEnd.set(event.clientX, event.clientY);
                    rotateDelta.subVectors(rotateEnd, rotateStart);
                    scope.rotateLeft(2 * Math.PI * rotateDelta.x / element.clientWidth * scope.rotateSpeed);
                    scope.rotateUp(2 * Math.PI * rotateDelta.y / element.clientHeight * scope.rotateSpeed);
                    rotateStart.copy(rotateEnd);
                } else if (state === STATE.DOLLY) {
                    if (scope.noZoom === true)
                        return;
                    dollyEnd.set(event.clientX, event.clientY);
                    dollyDelta.subVectors(dollyEnd, dollyStart);
                    if (dollyDelta.y > 0) {
                        scope.dollyIn();
                    } else {
                        scope.dollyOut();
                    }
                    dollyStart.copy(dollyEnd);
                } else if (state === STATE.PAN) {
                    if (scope.noPan === true)
                        return;
                    panEnd.set(event.clientX, event.clientY);
                    panDelta.subVectors(panEnd, panStart);
                    scope.pan(panDelta);
                    panStart.copy(panEnd);
                }
                scope.update();
            }
            function onMouseUp() {
                if (scope.enabled === false)
                    return;
                scope.domElement.removeEventListener('mousemove', onMouseMove, false);
                scope.domElement.removeEventListener('mouseup', onMouseUp, false);
                state = STATE.NONE;
            }
            function onMouseWheel(event) {
                if (scope.enabled === false || scope.noZoom === true)
                    return;
                var delta = 0;
                if (event.wheelDelta) {
                    delta = event.wheelDelta;
                } else if (event.detail) {
                    delta = -event.detail;
                }
                if (delta > 0) {
                    scope.dollyOut();
                } else {
                    scope.dollyIn();
                }
                scope.update();
            }
            function onKeyDown(event) {
                if (scope.enabled === false) {
                    return;
                }
                if (scope.noKeys === true) {
                    return;
                }
                if (scope.noPan === true) {
                    return;
                }
                switch (event.keyCode) {
                    case scope.keys.UP:
                        scope.pan(new THREE.Vector2(0, scope.keyPanSpeed));
                        break;
                    case scope.keys.BOTTOM:
                        scope.pan(new THREE.Vector2(0, -scope.keyPanSpeed));
                        break;
                    case scope.keys.LEFT:
                        scope.pan(new THREE.Vector2(scope.keyPanSpeed, 0));
                        break;
                    case scope.keys.RIGHT:
                        scope.pan(new THREE.Vector2(-scope.keyPanSpeed, 0));
                        break;
                }
            }
            function touchstart(event) {
                if (scope.enabled === false) {
                    return;
                }
                switch (event.touches.length) {
                    case 1:
                        if (scope.noRotate === true) {
                            return;
                        }
                        state = STATE.TOUCH_ROTATE;
                        rotateStart.set(event.touches[0].pageX, event.touches[0].pageY);
                        break;
                    case 2:
                        if (scope.noZoom === true) {
                            return;
                        }
                        state = STATE.TOUCH_DOLLY;
                        var dx = event.touches[0].pageX - event.touches[1].pageX;
                        var dy = event.touches[0].pageY - event.touches[1].pageY;
                        var distance = Math.sqrt(dx * dx + dy * dy);
                        dollyStart.set(0, distance);
                        break;
                    case 3:
                        if (scope.noPan === true) {
                            return;
                        }
                        state = STATE.TOUCH_PAN;
                        panStart.set(event.touches[0].pageX, event.touches[0].pageY);
                        break;
                    default:
                        state = STATE.NONE;
                }
                scope.update();
            }
            function touchmove(event) {
                if (scope.enabled === false) {
                    return;
                }
                event.preventDefault();
                event.stopPropagation();
                var element = scope.domElement === document ? scope.domElement.body : scope.domElement;
                switch (event.touches.length) {
                    case 1:
                        if (scope.noRotate === true) {
                            return;
                        }
                        if (state !== STATE.TOUCH_ROTATE) {
                            return;
                        }
                        rotateEnd.set(event.touches[0].pageX, event.touches[0].pageY);
                        rotateDelta.subVectors(rotateEnd, rotateStart);
                        scope.rotateLeft(2 * Math.PI * rotateDelta.x / element.clientWidth * scope.rotateSpeed);
                        scope.rotateUp(2 * Math.PI * rotateDelta.y / element.clientHeight * scope.rotateSpeed);
                        rotateStart.copy(rotateEnd);
                        break;
                    case 2:
                        if (scope.noZoom === true) {
                            return;
                        }
                        if (state !== STATE.TOUCH_DOLLY) {
                            return;
                        }
                        var dx = event.touches[0].pageX - event.touches[1].pageX;
                        var dy = event.touches[0].pageY - event.touches[1].pageY;
                        var distance = Math.sqrt(dx * dx + dy * dy);
                        dollyEnd.set(0, distance);
                        dollyDelta.subVectors(dollyEnd, dollyStart);
                        if (dollyDelta.y > 0) {
                            scope.dollyOut();
                        } else {
                            scope.dollyIn();
                        }
                        dollyStart.copy(dollyEnd);
                        break;
                    case 3:
                        if (scope.noPan === true) {
                            return;
                        }
                        if (state !== STATE.TOUCH_PAN) {
                            return;
                        }
                        panEnd.set(event.touches[0].pageX, event.touches[0].pageY);
                        panDelta.subVectors(panEnd, panStart);
                        scope.pan(panDelta);
                        panStart.copy(panEnd);
                        break;
                    default:
                        state = STATE.NONE;
                }
                scope.update();
            }
            function touchend() {
                if (scope.enabled === false) {
                    return;
                }
                state = STATE.NONE;
                scope.update();
            }
            this.domElement.addEventListener('contextmenu', function (event) {
                event.preventDefault();
            }, false);
            this.domElement.addEventListener('mousedown', onMouseDown, false);
            this.domElement.addEventListener('mousewheel', onMouseWheel, false);
            this.domElement.addEventListener('DOMMouseScroll', onMouseWheel, false);
            this.domElement.addEventListener('touchstart', touchstart, false);
            this.domElement.addEventListener('touchend', touchend, false);
            this.domElement.addEventListener('touchmove', touchmove, false);
            window.addEventListener('keydown', onKeyDown, false);
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.HUD = function (three) {
            var scope = this;
            var three = three;
            var scene = new THREE.Scene();
            var selectedItem = null;
            var rotating = false;
            var mouseover = false;
            var tolerance = 10;
            var height = 5;
            var distance = 20;
            var color = "#ffffff";
            var hoverColor = "#f1c40f";
            var activeObject = null;
            var activeMeasures = [];
            this.getScene = function () {
                return scene;
            };
            this.getObject = function () {
                return activeObject;
            };
            function init() {
                three.itemSelectedCallbacks.add(itemSelected);
                three.itemUnselectedCallbacks.add(itemUnselected);
            }
            function resetSelectedItem() {
                selectedItem = null;
                if (activeObject) {
                    scene.remove(activeObject);
                    activeObject = null;
                }
                if (activeMeasures.length) {
                    hideMeasure();
                    activeMeasures = [];
                }
                if ($('#set-colors').show()) {
                    $("#set-colors input").prop('checked', false);
                    $('#set-colors').hide()
                }
            }
            function itemSelected(item) {
                if (selectedItem != item) {
                    resetSelectedItem();
                    if (!item.fixed) {
                        selectedItem = item;
                        activeObject = makeObject(selectedItem);
                        if (item.allowRotate)
                            scene.add(activeObject);
                        showMeasure(selectedItem);
                    }
                }
            }
            function itemUnselected() {
                resetSelectedItem();
            }
            this.setRotating = function (isRotating) {
                rotating = isRotating;
                setColor();
            };
            this.setMouseover = function (isMousedOver) {
                mouseover = isMousedOver;
                setColor();
            };
            function setColor() {
                if (activeObject) {
                    activeObject.children.forEach(function (obj) {
                        obj.material.color.set(getColor());
                    });
                }
                three.needsUpdate();
            }
            function getColor() {
                return (mouseover || rotating) ? hoverColor : color;
            }
            this.update = function () {
                if (activeObject) {
                    activeObject.rotation.y = selectedItem.rotation.y;
                    activeObject.position.x = selectedItem.position.x;
                    activeObject.position.z = selectedItem.position.z;
                    hideMeasure();
                    showMeasure(selectedItem);
                }
            };
            function makeLineGeometry(item) {
                var geometry = new THREE.Geometry();
                geometry.vertices.push(new THREE.Vector3(0, 0, 0), rotateVector(item));
                return geometry;
            }
            function rotateVector(item) {
                var vec = new THREE.Vector3(0, 0, Math.max(item.halfSize.x, item.halfSize.z) + 1.4 + distance);
                return vec;
            }
            function makeLineMaterial(rotating) {
                var mat = new THREE.LineBasicMaterial({
                    color: getColor(),
                    linewidth: 3
                });
                return mat;
            }
            function makeCone(item) {
                var coneGeo = new THREE.CylinderGeometry(5, 0, 10);
                var coneMat = new THREE.MeshBasicMaterial({
                    color: getColor()
                });
                var cone = new THREE.Mesh(coneGeo, coneMat);
                cone.position.copy(rotateVector(item));
                cone.rotation.x = -Math.PI / 2.0;
                return cone;
            }
            function makeSphere(item) {
                var geometry = new THREE.SphereGeometry(4, 16, 16);
                var material = new THREE.MeshBasicMaterial({
                    color: getColor()
                });
                var sphere = new THREE.Mesh(geometry, material);
                return sphere;
            }
            function makeObject(item) {
                var object = new THREE.Object3D();
                var line = new THREE.Line(makeLineGeometry(item), makeLineMaterial(scope.rotating), THREE.LinePieces);
                var cone = makeCone(item);
                var sphere = makeSphere(item);
                object.add(line);
                object.add(cone);
                object.add(sphere);
                object.rotation.y = item.rotation.y;
                object.position.x = item.position.x;
                object.position.z = item.position.z;
                object.position.y = height;
                return object;
            }
            function measurementLabel(info, factor) {
                var distance = info.dis;
                var position = info.pos;
                var measure = Math.ceil(distance[factor] / 2.54);
                var measure_txt = measure + "\"";
                if (APP_UOM == "FT")
                    measure_txt = Math.floor(measure / 12) + "'" + (measure % 12) + "\"";
                var canvas = document.createElement('canvas');
                var tb_width = 120;
                var context = canvas.getContext('2d');
                context.fillStyle = 'blue';
                context.font = '24px sans-serif';
                context.fillText(measure_txt, 0, tb_width);
                var texture = new THREE.Texture(canvas);
                texture.needsUpdate = true;
                var material = new THREE.MeshBasicMaterial({
                    map: texture,
                    side: THREE.DoubleSide,
                })
                material.transparent = true;
                var mesh = new THREE.Mesh(new THREE.PlaneBufferGeometry(tb_width, 80), material);
                mesh.position.x = position.x + (tb_width / 2);
                mesh.position.y = position.y + 24;
                mesh.position.z = position.z;
                return mesh
            }
            function measureLe(item) {
                var object = new THREE.Object3D();
                var distance = item.position.x - item.halfSize.x;
                var color = "#ffffff";
                var geometry = new THREE.Geometry();
                geometry.vertices.push(new THREE.Vector3(distance, item.position.y, item.position.z), new THREE.Vector3(0, item.position.y, item.position.z));
                var material = new THREE.LineBasicMaterial({
                    color: color,
                    linewidth: 3
                });
                var line = new THREE.Line(geometry, material, THREE.LinePieces);
                line.name = "Left Measure Mark";
                object.add(line);
                let params = {
                    dis: {
                        x: distance,
                        y: item.position.y,
                        z: item.position.z
                    },
                    pos: {
                        x: distance / 2,
                        y: item.position.y,
                        z: item.position.z
                    }
                }
                var measurement = measurementLabel(params, "x");
                object.add(measurement);
                return object;
            }
            function measureRi(item) {
                var object = new THREE.Object3D();
                var distance = item.position.x + item.halfSize.x;
                var rightend = item.model.floorplan.getSize().x;
                var color = "#ffffff";
                var geometry = new THREE.Geometry();
                geometry.vertices.push(new THREE.Vector3(distance, item.position.y, item.position.z), new THREE.Vector3(rightend, item.position.y, item.position.z));
                var material = new THREE.LineBasicMaterial({
                    color: color,
                    linewidth: 3
                });
                var line = new THREE.Line(geometry, material, THREE.LinePieces);
                line.name = "Right Measure Mark";
                object.add(line);
                let params = {
                    dis: {
                        x: rightend - distance,
                        y: item.position.y,
                        z: item.position.z
                    },
                    pos: {
                        x: (distance + rightend) / 2,
                        y: item.position.y,
                        z: item.position.z
                    }
                }
                var measurement = measurementLabel(params, "x");
                object.add(measurement);
                return object;
            }
            function measureTo(item) {
                var object = new THREE.Object3D();
                var distance = item.position.y + item.halfSize.y
                var roofend = item.model.floorplan.getWalls()[0].height;
                var color = "#ffffff";
                var geometry = new THREE.Geometry();
                geometry.vertices.push(new THREE.Vector3(item.position.x, distance, item.position.z), new THREE.Vector3(item.position.x, roofend, item.position.z));
                var material = new THREE.LineBasicMaterial({
                    color: color,
                    linewidth: 3
                });
                var line = new THREE.Line(geometry, material, THREE.LinePieces);
                line.name = "Top Measure Mark";
                object.add(line);
                let params = {
                    dis: {
                        x: item.position.x,
                        y: roofend - distance,
                        z: item.position.z
                    },
                    pos: {
                        x: item.position.x,
                        y: (distance + roofend) / 2,
                        z: item.position.z
                    }
                }
                var measurement = measurementLabel(params, "y");
                object.add(measurement);
                return object;
            }
            function measureBo(item) {
                var object = new THREE.Object3D();
                var distance = item.position.y - item.halfSize.y
                var color = "#ffffff";
                var geometry = new THREE.Geometry();
                geometry.vertices.push(new THREE.Vector3(item.position.x, distance, item.position.z), new THREE.Vector3(item.position.x, 0, item.position.z));
                var material = new THREE.LineBasicMaterial({
                    color: color,
                    linewidth: 3
                });
                var line = new THREE.Line(geometry, material, THREE.LinePieces);
                line.name = "Bottom Measure Mark";
                object.add(line);
                let params = {
                    dis: {
                        x: item.position.x,
                        y: distance,
                        z: item.position.z
                    },
                    pos: {
                        x: item.position.x,
                        y: distance / 2,
                        z: item.position.z
                    }
                }
                if (params.dis.z != params.pos.z) {
                    var measurement = measurementLabel(params, "y");
                    object.add(measurement);
                }
                return object;
            }
            function measureFr(item) {
                let is_allowed = true;
                var object = new THREE.Object3D();
                if (is_allowed) {
                    var distance = item.position.z + item.halfSize.z;
                    var frontend = item.model.floorplan.getSize().z / 2;
                    var color = "#ffffff";
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(item.position.x, item.position.y, distance), new THREE.Vector3(item.position.x, item.position.y, frontend - item.halfSize.z * 2));
                    var material = new THREE.LineBasicMaterial({
                        color: color,
                        linewidth: 3
                    });
                    var line = new THREE.Line(geometry, material, THREE.LinePieces);
                    line.name = "Front Measure Mark";
                    object.add(line);
                    let params = {
                        dis: {
                            x: item.position.x,
                            y: item.position.y,
                            z: frontend - distance - item.halfSize.z * 2
                        },
                        pos: {
                            x: item.position.x,
                            y: item.position.y,
                            z: (distance + frontend - item.halfSize.z) / 2
                        }
                    }
                    var measurement = measurementLabel(params, "z");
                    object.add(measurement);
                }
                return object;
            }
            function measureBa(item) {
                let is_allowed = true;
                var object = new THREE.Object3D();
                if (is_allowed) {
                    var distance = item.position.z - item.halfSize.z;
                    var rightend = item.model.floorplan.getSize().z / 2;
                    var color = "#ffffff";
                    var geometry = new THREE.Geometry();
                    geometry.vertices.push(new THREE.Vector3(item.position.x, item.position.y, distance), new THREE.Vector3(item.position.x, item.position.y, -rightend - item.halfSize.z * 2));
                    var material = new THREE.LineBasicMaterial({
                        color: color,
                        linewidth: 3
                    });
                    var line = new THREE.Line(geometry, material, THREE.LinePieces);
                    line.name = "Back Measure Mark";
                    object.add(line);
                    let params = {
                        dis: {
                            x: item.position.x,
                            y: item.position.y,
                            z: rightend + distance + item.halfSize.z * 2
                        },
                        pos: {
                            x: item.position.x,
                            y: item.position.y,
                            z: (distance - rightend + item.halfSize.z) / 2
                        }
                    }
                    var measurement = measurementLabel(params, "z");
                    object.add(measurement);
                }
                return object;
            }
            function showMeasure(item) {
                activeMeasures = [measureLe(item), measureRi(item), measureTo(item), measureBo(item), measureFr(item), measureBa(item)];
                activeMeasures.forEach(obj => {
                    if (obj !== null)
                        scene.add(obj)
                })
            }
            function hideMeasure() {
                activeMeasures.forEach(obj => scene.remove(obj))
            }
            init();
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Three;
    (function (Three) {
        Three.Main = function (model, element, canvasElement, opts) {
            var scope = this;
            var options = {
                resize: true,
                pushHref: false,
                spin: true,
                spinSpeed: .00002,
                clickPan: true,
                canMoveFixedItems: false
            };
            for (var opt in options) {
                if (options.hasOwnProperty(opt) && opts.hasOwnProperty(opt)) {
                    options[opt] = opts[opt];
                }
            }
            var scene = model.scene;
            var model = model;
            this.element = $(element);
            var domElement;
            var camera;
            var renderer;
            this.controls;
            var canvas;
            var controller;
            var floorplan;
            var needsUpdate = false;
            var lastRender = Date.now();
            var mouseOver = false;
            var hasClicked = false;
            var hud;
            this.heightMargin;
            this.widthMargin;
            this.elementHeight;
            this.elementWidth;
            this.itemSelectedCallbacks = $.Callbacks();
            this.itemUnselectedCallbacks = $.Callbacks();
            this.wallClicked = $.Callbacks();
            this.floorClicked = $.Callbacks();
            this.nothingClicked = $.Callbacks();
            function init() {
                THREE.ImageUtils.crossOrigin = "";
                domElement = scope.element.get(0);
                camera = new THREE.PerspectiveCamera(45, 1, 1, 10000);
                renderer = new THREE.WebGLRenderer({
                    antialias: true,
                    preserveDrawingBuffer: true
                });
                renderer.autoClear = false,
                    renderer.shadowMapEnabled = true;
                renderer.shadowMapSoft = true;
                renderer.shadowMapType = THREE.PCFSoftShadowMap;
                var skybox = new Three.Skybox(scene);
                scope.controls = new Three.Controls(camera, domElement);
                hud = new Three.HUD(scope);
                controller = new Three.Controller(scope, model, camera, scope.element, scope.controls, hud);
                domElement.appendChild(renderer.domElement);
                scope.updateWindowSize();
                if (options.resize) {
                    $(window).resize(scope.updateWindowSize);
                }
                scope.centerCamera();
                model.floorplan.fireOnUpdatedRooms(scope.centerCamera);
                var lights = new Three.Lights(scene, model.floorplan);
                floorplan = new Three.Floorplan(scene, model.floorplan, scope.controls);
                animate();
                scope.element.mouseenter(function () {
                    mouseOver = true;
                }).mouseleave(function () {
                    mouseOver = false;
                }).click(function () {
                    hasClicked = true;
                });
            }
            function spin() {
                if (options.spin && !mouseOver && !hasClicked) {
                    var theta = 2 * Math.PI * options.spinSpeed * (Date.now() - lastRender);
                    scope.controls.rotateLeft(theta);
                    scope.controls.update();
                }
            }
            this.dataUrl = function () {
                var dataUrl = renderer.domElement.toDataURL("image/png");
                return dataUrl;
            };
            this.stopSpin = function () {
                hasClicked = true;
            };
            this.options = function () {
                return options;
            };
            this.getModel = function () {
                return model;
            };
            this.getScene = function () {
                return scene;
            };
            this.getController = function () {
                return controller;
            };
            this.getCamera = function () {
                return camera;
            };
            this.needsUpdate = function () {
                needsUpdate = true;
            };
            function shouldRender() {
                if (scope.controls.needsUpdate || controller.needsUpdate || needsUpdate || model.scene.needsUpdate) {
                    scope.controls.needsUpdate = false;
                    controller.needsUpdate = false;
                    needsUpdate = false;
                    model.scene.needsUpdate = false;
                    return true;
                } else {
                    return false;
                }
            }
            function render() {
                spin();
                if (shouldRender()) {
                    renderer.clear();
                    renderer.render(scene.getScene(), camera);
                    renderer.clearDepth();
                    renderer.render(hud.getScene(), camera);
                }
                lastRender = Date.now();
            };
            function animate() {
                var delay = 50;
                setTimeout(function () {
                    requestAnimationFrame(animate);
                }, delay);
                render();
            };
            this.rotatePressed = function () {
                controller.rotatePressed();
            };
            this.rotateReleased = function () {
                controller.rotateReleased();
            };
            this.setCursorStyle = function (cursorStyle) {
                domElement.style.cursor = cursorStyle;
            };
            this.updateWindowSize = function () {
                scope.heightMargin = scope.element.offset().top;
                scope.widthMargin = scope.element.offset().left;
                scope.elementWidth = scope.element.innerWidth();
                if (options.resize) {
                    scope.elementHeight = window.innerHeight - scope.heightMargin;
                } else {
                    scope.elementHeight = scope.element.innerHeight();
                }
                camera.aspect = scope.elementWidth / scope.elementHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(scope.elementWidth, scope.elementHeight);
                needsUpdate = true;
            };
            this.centerCamera = function () {
                var yOffset = 150.0;
                var pan = model.floorplan.getCenter();
                pan.y = yOffset;
                scope.controls.target = pan;
                var distance = model.floorplan.getSize().z * 1.5;
                var offset = pan.clone().add(new THREE.Vector3(0, distance, distance));
                camera.position.copy(offset);
                scope.controls.update();
            };
            this.topCamera = function (type = '',) {
                var yOffset = 150.0;
                var xOffst = 0
                var pan = model.floorplan.getCenter();
                pan.y = yOffset;
                scope.controls.target = pan;
                var distance = model.floorplan.getSize().z * 1.5;
                if (type == 'straight') {


                    // console.log(index)
                    // var offset = pan.clone().add(new THREE.Vector3(0, distance * 1.3, 0));
                    var offset = pan.clone().add(new THREE.Vector3(0, - (distance * 1.3), distance / 3));

                }
                else if (type == 'top')
                    var offset = pan.clone().add(new THREE.Vector3(0, (distance * 1.3), 0));
                // var offset = pan.clone().add(new THREE.Vector3(-349, 348, 407));

                camera.position.copy(offset);
                scope.controls.update();
            }
            this.projectVector = function (vec3, ignoreMargin) {
                ignoreMargin = ignoreMargin || false;
                var widthHalf = scope.elementWidth / 2;
                var heightHalf = scope.elementHeight / 2;
                var vector = new THREE.Vector3();
                vector.copy(vec3);
                vector.project(camera);
                var vec2 = new THREE.Vector2();
                vec2.x = (vector.x * widthHalf) + widthHalf;
                vec2.y = - (vector.y * heightHalf) + heightHalf;
                if (!ignoreMargin) {
                    vec2.x += scope.widthMargin;
                    vec2.y += scope.heightMargin;
                }
                return vec2;
            };
            var prev_wallObj = {}
            this.setWallName = function (obj) {
                if (prev_wallObj && prev_wallObj != null)
                    scene.remove(prev_wallObj)
                prev_wallObj = this.setCurrentWallName(obj)
                scene.add(prev_wallObj);

                model.scene.needsUpdate = true;
                render();
            }
            this.setCurrentWallName = function (obj) {
                var canvas = document.createElement('canvas');
                var tb_width = 100;
                var padding = 10;

                var context = canvas.getContext('2d');
                context.fillStyle = '#F88421';
                context.fillRect(0, 0, tb_width, 50);

                context.fillStyle = 'white';
                context.font = '36px sans-serif';
                var textHeight = 36;
                var textY = padding + textHeight;

                context.fillText(obj.name, 0, textY);

                context.fillText('', 0, textY + padding);
                var color = "red"
                var material = new THREE.LineBasicMaterial({

                    color: color,
                    linewidth: 3
                });

                // context.fillStyle = '#F88421';
                // context.fillRect(0, textY + 2 * padding, context.canvas.width, 5); 
                var texture = new THREE.Texture(canvas);
                texture.needsUpdate = true;
                var material = new THREE.MeshBasicMaterial({
                    map: texture,
                    side: THREE.DoubleSide,
                })
                material.transparent = true;
                var mesh = new THREE.Mesh(new THREE.PlaneBufferGeometry(tb_width, 52), material);
                mesh.position.x = obj.center.x + 20;
                mesh.position.y = obj.top + 5;
                mesh.position.z = obj.center.y;
                return mesh
            }
            init();
        };
    })(Three = BP3D.Three || (BP3D.Three = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Blueprint3d = (function () {
        function Blueprint3d(options) {
            this.model = new BP3D.Model.Model(options.textureDir);
            this.three = new BP3D.Three.Main(this.model, options.threeElement, options.threeCanvasElement, {});
            if (!options.widget) {
                this.floorplanner = new BP3D.Floorplanner.Floorplanner(options.floorplannerElement, this.model.floorplan);
            } else {
                this.three.getController().enabled = false;
            }
        }
        return Blueprint3d;
    })();
    BP3D.Blueprint3d = Blueprint3d;
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Core;
    (function (Core) {
        (function (ELogContext) {
            ELogContext[ELogContext["None"] = 0] = "None";
            ELogContext[ELogContext["All"] = 1] = "All";
            ELogContext[ELogContext["Interaction2d"] = 2] = "Interaction2d";
            ELogContext[ELogContext["Item"] = 3] = "Item";
            ELogContext[ELogContext["Wall"] = 4] = "Wall";
            ELogContext[ELogContext["Room"] = 5] = "Room";
        })(Core.ELogContext || (Core.ELogContext = {}));
        var ELogContext = Core.ELogContext;
        (function (ELogLevel) {
            ELogLevel[ELogLevel["Information"] = 0] = "Information";
            ELogLevel[ELogLevel["Warning"] = 1] = "Warning";
            ELogLevel[ELogLevel["Error"] = 2] = "Error";
            ELogLevel[ELogLevel["Fatal"] = 3] = "Fatal";
            ELogLevel[ELogLevel["Debug"] = 4] = "Debug";
        })(Core.ELogLevel || (Core.ELogLevel = {}));
        var ELogLevel = Core.ELogLevel;
        Core.logContext = ELogContext.None;
        function isLogging(context, level) {
            return Core.logContext === ELogContext.All || Core.logContext == context || level === ELogLevel.Warning || level === ELogLevel.Error || level === ELogLevel.Fatal;
        }
        Core.isLogging = isLogging;
        function log(context, level, message) {
            if (isLogging(context, level) === false) {
                return;
            }
            var tPrefix = "";
            switch (level) {
                case ELogLevel.Information:
                    tPrefix = "[INFO_] ";
                    break;
                case ELogLevel.Warning:
                    tPrefix = "[WARNG] ";
                    break;
                case ELogLevel.Error:
                    tPrefix = "[ERROR] ";
                    break;
                case ELogLevel.Fatal:
                    tPrefix = "[FATAL] ";
                    break;
                case ELogLevel.Debug:
                    tPrefix = "[DEBUG] ";
                    break;
            }
            console.log(tPrefix + message);
        }
        Core.log = log;
    })(Core = BP3D.Core || (BP3D.Core = {}));
})(BP3D || (BP3D = {}));
var BP3D;
(function (BP3D) {
    var Core;
    (function (Core) {
        var Version = (function () {
            function Version() { }
            Version.getInformalVersion = function () {
                return "1.0 Beta 1";
            };
            Version.getTechnicalVersion = function () {
                return "1.0.0.1";
            };
            return Version;
        })();
        Core.Version = Version;
    })(Core = BP3D.Core || (BP3D.Core = {}));
})(BP3D || (BP3D = {}));
// console.log("Blueprint3D " + BP3D.Core.Version.getInformalVersion() + " (" + BP3D.Core.Version.getTechnicalVersion() + ")");
