{
    "metadata": {
        "type": "Object",
        "version": 4.3,
        "generator": "io_three"
    },
    "geometries": [{
        "uuid": "FE46F7EB-A263-3807-8987-EB40ABE18EAA",
        "type": "Geometry",
        "materials": [{
            "opacity": 1,
            "DbgName": "Material",
            "depthTest": true,
            "colorEmissive": [0,0,0],
            "shading": "phong",
            "colorAmbient": [0.64,0.64,0.64],
            "wireframe": false,
            "blending": "NormalBlending",
            "depthWrite": true,
            "DbgIndex": 0,
            "specularCoef": 50,
            "DbgColor": 15658734,
            "colorDiffuse": [0.64,0.64,0.64],
            "visible": true,
            "transparent": false,
            "colorSpecular": [0.5,0.5,0.5]
        }],
        "data": {
            "metadata": {
                "vertices": 8,
                "uvs": 0,
                "faces": 6,
                "generator": "io_three",
                "materials": 1,
                "bones": 0,
                "normals": 8,
                "version": 3
            },
            "normals": [0.577349,0.577349,-0.577349,0.577349,-0.577349,-0.577349,-0.577349,-0.577349,-0.577349,-0.577349,0.577349,-0.577349,0.577349,0.577349,0.577349,-0.577349,0.577349,0.577349,-0.577349,-0.577349,0.577349,0.577349,-0.577349,0.577349],
            "uvs": [],
            "faces": [35,0,1,2,3,0,0,1,2,3,35,4,7,6,5,0,4,5,6,7,35,0,4,5,1,0,0,4,7,1,35,1,5,6,2,0,1,7,6,2,35,2,6,7,3,0,2,6,5,3,35,4,0,3,7,0,4,0,3,5],
            "name": "CubeGeometry",
            "influencesPerVertex": 2,
            "bones": [],
            "skinIndices": [],
            "vertices": [1,1,-1,1,-1,-1,-1,-1,-1,-1,1,-1,1,0.999999,1,0.999999,-1,1,-1,-1,1,-1,1,1],
            "skinWeights": []
        }
    }],
    "textures": [],
    "materials": [{
        "ambient": 10724259,
        "depthTest": true,
        "shininess": 50,
        "vertexColors": false,
        "type": "MeshPhongMaterial",
        "blending": "NormalBlending",
        "depthWrite": true,
        "emissive": 0,
        "uuid": "FC9C3C1C-87E7-3748-8148-105003E5F3D5",
        "color": 10724259,
        "name": "Material",
        "specular": 8355711
    }],
    "object": {
        "uuid": "8A4C09C2-7396-4E56-BCE2-37BC41436398",
        "type": "Scene",
        "children": [{
            "name": "Camera",
            "uuid": "DE0714B3-5D16-30EF-9A0A-6C5D6DD394B9",
            "matrix": [-0.685921,0,0.727676,0,0.324013,0.895396,0.305421,0,-0.651558,0.445271,-0.61417,0,-7.48113,5.34366,-6.50764,1],
            "visible": true,
            "type": "PerspectiveCamera",
            "far": 100,
            "near": 0.1,
            "aspect": 1.77778,
            "fov": 35
        },{
            "name": "Cube",
            "uuid": "F29D1AC4-D789-3D8E-BAB5-59DC1B758FE2",
            "matrix": [-1,0,0,0,0,0,1,0,0,1,0,0,0,0,0,1],
            "visible": true,
            "type": "Mesh",
            "material": "FC9C3C1C-87E7-3748-8148-105003E5F3D5",
            "castShadow": true,
            "receiveShadow": true,
            "geometry": "FE46F7EB-A263-3807-8987-EB40ABE18EAA"
        },{
            "name": "Lamp",
            "uuid": "480358C5-FC08-314B-A146-DEE8993A0407",
            "matrix": [0.290865,-0.055189,0.955171,0,0.771101,0.604525,-0.199883,0,-0.566393,0.794672,0.218391,0,-4.07625,5.90386,1.00545,1],
            "visible": true,
            "type": "PointLight",
            "color": 16777215,
            "intensity": 1,
            "distance": 30
        }],
        "matrix": [1,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1]
    },
    "images": []
}