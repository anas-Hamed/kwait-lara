@php
    $images = isset($entry) ? $entry[$field['relation']]->toArray() : [];
    $aspect_ratio = isset($field['aspect_ratio']) ? $field['aspect_ratio']:  4/3;
    if (old($field['name'])){
        $images = array_map(function ($el){
        return json_decode($el);
    },old($field['name']));
    }
@endphp
<h2>{{$field['label']}}</h2>
<div id="app" class="w-100">
    <div class=" text-center col-12" id="wrdUploadFiles">
        <div class="row mx-0 w-100 pic-container">
            <div class="col-md-4">
                <div v-if="original_images.length == 0" class="cropping-side">
                    <i class="las la-crop-alt la-4x mt-5 pt-3"></i>
                    <p class="p-2 mt-4">هنا يمكنك تأطير الصور بما يتناسب مع الحجم المعتمد في الموقع</p>
                </div>
                <div v-for="(img,index) in original_images">
                    <cropper v-if="index == show && img.image == null" :src="img" :index="index" @croped="pushCroped"/>
                </div>
                <div v-if="show >= original_images.length && original_images.length > 0 " class="cropping-side">
                    <i class="fas fa-crop-alt fa-4x mt-5 pt-3"></i>
                    <p class="p-2 mt-4">يمكنك اختيار صورة للتعديل</p>
                </div>
            </div>
            <div class="col-md-8 border-md-left border-success position-relative">

                <i v-if="v_images.length == 0"
                   class="la la-file-image-o la-5x mt-5 pt-3" aria-hidden="true"></i><br/>
                <div class="imgs-con py-2 mt-4" v-if="v_images.length > 0">
                    <div v-for="(img,index) in v_images" :key="index" class="imgs">
                        <button @click="deleteImg(index,img.id)" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <img @drop="drop" @dragover="allowDrop" draggable="true" @dragstart="drag" :id="index"
                             @click="goTo(index )" class="img" :src="img['{!! $field["attribute"] !!}'] ?  img['{!! $field["attribute"] !!}']:img"/>
                    </div>
                </div>
                <button type="button" @click.prevent="$refs.files.click()"
                        class="btn btn-success px-0 px-md-1 w-50 my-2 mt-5" style="background-color: #015fa9;border: none">
                    <i class="la la-upload"> @{{v_images.length == 0 ? "اختيار الصور":"إضافة صور"}} </i></button>
            </div>
        </div>
        <input v-if="v_images.length > 0" name="images[]" type="hidden" v-for="input in inputs"
               :value="JSON.stringify(input)">
        <input accept="image/*" @change="selcetFiles" type="file" multiple name="files"
               id="files" ref="files" style="display: none;">
    </div>
</div>
@push('crud_fields_scripts')
{{--    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script>
        Vue.component('cropper', {
            template: `    <div class="container-fluid">
        <div class="my-4">
            <div class="text-center mb-2">
                <div class="img-container mx-auto border" style="width: 200px;height: {!! 200 / $aspect_ratio !!}px">
                    <img  style="width: 200px;height: 150px"  ref="image" :src="src" crossorigin>
                </div>
            </div>
            <div class="text-center">
                <button @click.prevent="save" class="btn btn-success d-block m-auto  w-50">حفظ</button>
            </div>
        </div>
    </div>`,
            name: "Cropper",
            data() {
                return {
                    cropper: {},
                    destination: {},
                    image: {},
                    croppedImage: {},
                    canvas: {}
                }
            },
            props: {
                src: String,
                index: Number,
            },
            mounted() {
                this.image = this.$refs.image;
                this.cropData(this.image);
            },
            methods: {
                save() {
                    const canvas = this.cropper.getCroppedCanvas({width: 1000, height: {!! 1000 / $aspect_ratio !!},fillColor: '#fff'});
                    this.addWaterMark(canvas);
                },
                cropData(img) {
                    var s = this;
                    this.cropper = new Cropper(img, {
                        viewMode:0,
                        minContainerWidth: 200,
                        minContainerHeight: {!! 200 / $aspect_ratio !!},
                        minCanvasWidth: 200,
                        minCanvasHeight: {!! 200 / $aspect_ratio !!},
                        minCropBoxWidth: 200,
                        minCropBoxHeight: {!! 200 / $aspect_ratio !!},
                        autoCropArea: 1,
                        aspectRatio: {!! $aspect_ratio !!},
                        ready(event) {
                            s.save();
                        }
                    })
                },
                addWaterMark(canvas) {
                    this.destination = canvas.toDataURL("image/jpeg");
                    canvas.toBlob(o => {
                        this.croppedImage = o;
                        var croped = {};
                        croped.img = this.croppedImage;
                        croped.v_img = this.destination;
                        croped.index = this.index;
                        EventBus.$emit("cropped", croped);
                    })
                }
            }
        });
        const EventBus = new Vue();
        var app = new Vue({
            el: '#app',
            name: 'wrdUploadFiles',
            data() {
                return {
                    images: [],
                    v_images: [],
                    original_images: [],
                    del_imgs: [],
                    show: -1,
                    save: false
                }
            },
            props: {
                // coming_imgs: {
                //     default: []
                // },
            },
            methods: {
                drag(ev) {
                    ev.dataTransfer.setData("text", ev.target.id);
                },
                drop(ev) {
                    ev.preventDefault();
                    var data = ev.dataTransfer.getData("text");
                    let tran = this.v_images[data];
                    let target = Number(ev.target.id);
                    this.v_images = [...this.v_images.filter(el => el.image !== tran.image)];
                    this.v_images.splice(target, 0, tran);
                    this.v_images = [...this.v_images]
                },
                allowDrop(ev) {
                    ev.preventDefault();
                },
                selcetFiles() {
                    var files = this.$refs.files.files;
                    var self = this;
                    if (files) {
                        Array.from(files).forEach(el => {
                            var reader = new FileReader();
                            reader.onload = e => {
                                let img = {};
                                img.id = null;
                                img['{!! $field["attribute"] !!}'] = e.target.result;
                                self.v_images.push(img);
                                self.original_images.push(e.target.result);
                                self.show = (self.v_images?.length - 1 - this.comingImgs?.length) + this.del_imgs?.length;
                            };
                            reader.readAsDataURL(el);
                            self.images.push(el)
                        })
                    }
                },
                deleteImg(index, id) {
                    this.images.splice(index, 1);
                    this.v_images.splice(index, 1);
                    this.original_images.splice(index, 1);
                    if (id) {
                        this.del_imgs.push(id)
                    }
                },
                pushCroped(variable) {
                    this.images [variable.index] = variable.img;
                    var t = this.v_images;
                    t[variable.index + this.comingImgs.length - this.del_imgs.length] = {
                        id: null,
                        '{!! $field["attribute"] !!}': variable.v_img
                    };
                    this.v_images = [...t]
                },
                goTo(index) {

                    this.show = index - this.comingImgs.length + this.del_imgs.length;
                }
            },
            mounted() {
                if (this.comingImgs?.length > 0) {
                    this.comingImgs.forEach(el => {
                        var img = {};
                        img.id = el.id || null;
                        img['{!! $field["attribute"] !!}'] = el.id ? '/storage/' + el['{!! $field["attribute"] !!}'].replace('/storage/', '') : el['{!! $field["attribute"] !!}'];
                        this.v_images.push(img)
                    })
                }
                EventBus.$on("cropped", this.pushCroped)
            },
            watch: {
                comingImgs() {
                    if (this.comingImgs?.length > 0) {
                        this.comingImgs.forEach(el => {
                            var img = {};
                            img.id = el.id || null;
                            img['{!! $field["attribute"] !!}'] = el.id ? '/storage/' + el['{!! $field["attribute"] !!}'].replace('/storage/', '') : el['{!! $field["attribute"] !!}'];
                            this.v_images.push(img)
                        })
                    }
                },
            },
            computed: {
                comingImgs() {
                    return {!! json_encode($images) !!}
                },
                inputs() {
                    let inputs = [];
                    this.v_images.forEach(el => {
                        inputs.push({...el, order: this.v_images.indexOf(el) + 1})
                    });
                    return inputs;
                }
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js"></script>
@endpush
@push('crud_fields_styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css">
    <style>
        .cropping-side {
            color: #91938f;
            font-weight: normal;
        }
        .imgs-con {
            overflow: auto;
            white-space: nowrap;
        }
        .pic-container {
            background: #e4e4e4;
            border: dashed #124477 0.3px;
            min-height: 350px;
            padding: 5px;
            position: relative;
            margin-bottom: 10px;
        }
        .imgs {
            width: 130px;
            height: 82px;
            padding: 1px;
            position: relative;
            display: inline;
            direction: ltr;
            margin-bottom: 5px !important;
        }
        .close {
            position: absolute;
            left: 5px;
            top: -20px;
        }
        .close span {
            line-height: 0.2;
        }
        .img {
            width: 120px;
            height: {!! 120 / $aspect_ratio !!}px;
            object-fit: cover;
            cursor: pointer;
        }
    </style>
@endpush
