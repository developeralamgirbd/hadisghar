<template>
    <AdminLayout title="Update Post">
            <h1 class="uppercase text-green-500 text-2xl my-3 text-center">Edit Post</h1>
            <div class="md:flex justify-between items-center pl-2 md:pl-6">
                <div class="mb-4 md:mb-0">
                    <Link :href="route('admin.post.index')" preserve-scroll class="text-gray-50 bg-green-500 px-4 py-3 text-lg rounded"><fa icon="fa-arrow-left"></fa> Back</Link>
                </div>
            </div>
            <div class="p-2 md:p-6 w-full h-full">
                <div class="">
                    <form @submit.prevent="postUpdate" enctype="multipart/form-data" class="bg-white rounded">
                        <input type="hidden" id="token" name="_token" :value="$page.props.csrf_token">
                        <div class="input-wrap mb-6 rounded">
                            <div class="input-group w-full flex items-center">
                                <p for="slug" class="text-gray-800 text-lg flex items-center">Permalink: {{ form.slug ? hostname+'post/'+form.slug : ''}}</p>
                                <input type="text" disabled id="slug" name="slug" v-model="form.slug" class="w-full border-none bg-transparent hidden" >
                            </div>
                        </div>
                        <div class="input-wrap mb-6 rounded">
                            <div class="input-group w-full">
                                <label for="title" class="text-gray-800 text-lg">Title<span class="text-red-600">*</span></label>
                                <input type="text" id="title" name="title" v-model="form.title"
                                       class="w-full focus:border-indigo-300 focus:ring-indigo-300 rounded-[5px] border-gray-300"
                                       :class="{'border-red-500' : errors.title}"
                                >
                                <p class="text-red-500" v-if="errors.title">{{ errors.title }}</p>
                            </div>
                        </div>


                        <div class="input-wrap mb-6 rounded">
                            <div class="input-group w-full">
                                <label for="" class="text-gray-800 text-lg">Description<span class="text-red-600">*</span></label>
                                <editor class="tinyEditor" v-model="form.description"
                                        api-key="f0nepqqrlzipwloln88xx5ltuz5ffy9trzchxjkgphxa2u4y"
                                        :plugins="tinyPlugins"
                                        :toolbar="tinyToolbar"
                                        :init="tinyInit">
                                </editor>
                            </div>
                        </div>

                        <div class="input-wrap mb-6 rounded">
                            <div class="input-group w-full">
                                <label for="metaDescription" class="text-gray-800 text-lg">SEO Meta Description<span class="text-red-600">*</span></label>
                                <textarea type="text"
                                          rows="3"
                                          id="metaDescription"
                                          name="meta_description"
                                          v-model="form.meta_description"
                                          class="w-full focus:border-indigo-300 focus:ring-indigo-300 rounded-[5px] border-gray-300"
                                          :class="{'border-red-500' : errors.meta_description}"
                                ></textarea>
                                <progress id="progress" max="156" :value="form.meta_description.length" class="block w-full"></progress>
                                <p class="text-red-500" v-if="errors.meta_description">{{ errors.meta_description }}</p>
                            </div>
                        </div>

                        <div class="input-wrap lg:w-1/3 mb-6 rounded">
                            <div class="input-group w-full">
                                <label for="" class="text-lg text-gray-800">Category<span class="text-red-600">*</span></label>
                                <select name="category" v-model="form.category" id="" class="w-full focus:border-indigo-300 focus:ring-indigo-300 rounded-[5px] border-gray-300 text-gray-800">
                                    <option selected disabled value=""> Select category</option>
                                    <option v-for="(category, index) in categories" :key="category.id" :value="category.id">{{ category.category_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="input-wrap mb-6 rounded lg:flex lg:gap-6">

                            <div class="input-group w-full md:w-2/3 lg:w-2/4 relative max-h-[200px] mb-10" :class="photoPreview || feature_img ? 'hover:bg-gray-800 hover:opacity-25' : ''">
                                <label for="" class="text-lg text-gray-800">Feature Image (Optional)</label>
                                <div class="" :class="photoPreview || feature_img ? 'block w-full h-[200px]' : 'w-full bg-gray-100 rounded h-[200px] flex justify-center items-center cursor-pointer border-gray-50 border-2'">
                                            <span :class="photoPreview || feature_img ? 'block' : 'hidden'"
                                                  class="block  w-full h-full bg-cover bg-no-repeat bg-center"
                                                  :style=" photoPreview ? 'background-image: url(\'' + photoPreview + '\');' : 'background-image: url(\'' + feature_img + '\');'"
                                            />
                                    <fa icon="fa-cloud-arrow-up" class="text-[60px] text-gray-300" :class="photoPreview || feature_img ? 'hidden' : 'text-center'"/>

                                </div>
                                <input ref="photoInput" @change="updatePhotoPreview" @input="form.photo = $event.target.files[0]" type="file" class="absolute w-full h-full left-0 top-0 opacity-0 cursor-pointer">
                            </div>

                            <!---------------------------------
                               ! Google Preview
                               ----------------------------------->
                            <div class="border rounded w-full lg-w-2/4 p-2 lg:p-6 my-4 overflow-x-scroll lg:overflow-hidden">
                                <h1 class="mb-4">Google preview</h1>
                                <b>Preview as:</b>
                                <div class="mb-4 flex gap-4 items-center mt-4">
                                    <p class="flex items-center gap-1">
                                        <input id="mobileResult" type="radio" checked @change="previewMob" name="result" class="cursor-pointer">
                                        <label for="mobileResult" class="cursor-pointer">Mobile result</label>
                                    </p>
                                    <p class="flex items-center gap-1">
                                        <input id="desktopResult" type="radio"  @change="previewDesk" name="result" class="cursor-pointer">
                                        <label for="desktopResult" class="cursor-pointer">Desktop result</label>
                                    </p>
                                </div>
                                <div v-if="mobilePreview" class="max-w-[400px] border rounded shadow-lg bg-white p-4">
                                    <div class="mb-1 text-[#202124] cursor-pointer">
                                        <span>{{hostname}} ></span>
                                    </div>
                                    <h3 class="text-[20px] font-bold text-[#1A0DAB] cursor-pointer">{{ form.title + ' - '+  $page.props.app_name }}</h3>
                                    <p class="text-[14px] text-[#4D5156] cursor-pointer">{{ form.meta_description }}</p>
                                </div>

                                <div v-if="desktopPreview" class="w-[600px] lg:w-full">
                                    <div class="mb-1 text-[#202124] cursor-pointer">
                                        <span>{{hostname}} ></span>
                                    </div>
                                    <h3 class="text-[20px] font-bold text-[#1A0DAB] cursor-pointer">{{ form.title + ' - '+  $page.props.app_name }}</h3>
                                    <p class="text-[14px] text-[#4D5156] cursor-pointer w-full">{{ form.meta_description }}</p>
                                </div>
                            </div>
                        </div>

                        <div v-if="permissionArr.includes('published post')" class="input-wrap lg:w-1/3 mb-6 rounded">
                            <div class="input-group w-full">
                                <label for="status" class="text-lg text-gray-800">Status<span class="text-red-600">*</span></label>
                                <select name="status" v-model="form.status" id="status" class="w-full focus:border-indigo-300 focus:ring-indigo-300 rounded-[5px] border-gray-300 text-gray-800">
                                    <option selected disabled value=""> Post status </option>
                                    <option value="draft"> Draft </option>
                                    <option value="published"> Published </option>
                                </select>
                            </div>
                        </div>
                        <div class="input-wrap pb-12 rounded mb-6">
                            <button class="bg-green-500 text-lg py-2 px-6 rounded text-green-50 flex items-center gap-1" type="submit" :class="{ 'opacity-25': form.processing || disable === true}" :disabled="form.processing || disable === true">

                                <svg v-if="form.processing" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" class="w-[30px]" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                    <path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                        <animateTransform
                                            attributeName="transform"
                                            attributeType="XML"
                                            type="rotate"
                                            dur="1s"
                                            from="0 50 50"
                                            to="360 50 50"
                                            repeatCount="indefinite" />
                                    </path>
                                    </svg>
                                <span v-if="permissionArr.includes('published post')">Update</span>
                                <span v-else>Submit For Admin Review</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </AdminLayout>
</template>
<script>
import {useForm} from "@inertiajs/inertia-vue3";
import {ref} from "vue";
import moment from "moment";
export default {
    name: "Edit",
    props: {
        responseForm: Array,
    },

    data(){
        return{
            moment: moment,
            permissionArr: [],
            isPublished: null,
            editor: Editor,
            tinyPlugins: 'autoresize print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',

            tinyToolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            tinyInit: {
                image_class_list: [
                    {title: 'img-responsive', value: 'img-responsive'},
                ],
                toolbar_sticky: true,
                content_style: "@import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap');",
                font_formats: "Naskh Arabic=Noto Naskh Arabic, serif;" ,
                fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 40pt 48pt 52pt 60pt 72pt 96pt",

                height: 800,
                setup: function (editor) {
                    editor.on('init change', function () {
                        editor.save();
                    });
                },
                image_title: true,
                automatic_uploads: true,
                images_upload_url: '/upload',
                file_picker_types: 'image',
                relative_urls: false,
            },
        }
    },
    mounted() {
        this.$page.props.userPermissions.forEach(item =>
            this.permissionArr.push(item.name),
        );
    },

}
</script>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {onMounted, ref, watch} from "vue";
import {useForm, Link,} from "@inertiajs/inertia-vue3";
import Editor from '@tinymce/tinymce-vue';
import Swal from "sweetalert2";
const props = defineProps({
    user: Object,
    errors: Object,
    categories: Object,
    post: Object

});

const form = useForm({
    _method: 'POST',
    title: '',
    slug: '',
    description: '',
    meta_description: '',
    category: '',
    photo: null,
    old_feature: null,
    status: ''
});
const err = {
    error: Boolean,
    disable: true
}
let isTrue = false;

const photoPreview = ref(null);
const photoInput = ref(null);

const hostname = window.location.origin+'/';
let post_id = ref(null);
let checkForm = ref(0);
const disable = ref(true);
let feature_img = ref(null);
let lengthSeoMeta = ref(null);
let seoMetaCheck = ref(null);

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};

const mobilePreview = ref(true);
const desktopPreview = ref(false);
const previewMob = () =>{
    mobilePreview.value = true;
    desktopPreview.value = false;
};
const previewDesk = () =>{
    mobilePreview.value = false;
    desktopPreview.value = true;
};


watch(form, (current, old) => {
        if(Object.values(form)[1] !== "" && Object.values(form)[2] !== "" && Object.values(form)[3] !== "" && Object.values(form)[4] !== "" && Object.values(form)[5] !== ""){
            disable.value = false;
            checkForm.value += 1;
        }else{
            disable.value = true
        }
    let titleSlug = form.title.replace(/([' ','@','#','%','^','&','*','(',')','|','~','`','"',"'",'!','৥৳','%','ঃ',"\\/"]+)/g, '-');
    form.slug = titleSlug;
    lengthSeoMeta.value = form.meta_description.length;
    if (form.meta_description !== ''){
        if (form.meta_description.length < 145 || form.meta_description.length > 156 && form.meta_description.length !== 0){
            seoMetaCheck.value = true;
            const progressEl = document.getElementById('progress')
            progressEl.style.setProperty('--progressbar-background', '#EE7C1B')
        }else if(form.meta_description.length === 0){
            seoMetaCheck.value = false;
        }else {
            seoMetaCheck.value = false;
            const progressEl = document.getElementById('progress')
            progressEl.style.setProperty('--progressbar-background', '#7AD03A')
        }
    }
    });

onMounted(() => {
    form.title = props.post.title;
    form.slug = props.post.slug;
    form.description = props.post.description;
    form.meta_description = props.post.meta_description;
    form.category = props.post.category_id;
    form.status = props.post.status;
    form.old_feature = props.post.feature_img;
    feature_img.value = props.post.feature_img;
    post_id.value = props.post.id;

})

const postUpdate = function() {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }
    isTrue = true;
    form.post(route('admin.post.update', post_id.value), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            }).fire({
                icon: 'success',
                title: 'Post updated successfully'
            })
        }
    })
}
</script>

<style scoped>
progress{
    --progressbar-background: red;
}
progress {
    height: 10px;
    border: 1px solid #dedede;
}
progress::-webkit-progress-bar {
    background-color: transparent;
}
progress::-webkit-progress-value {
    background-color: var(--progressbar-background);
}
</style>

