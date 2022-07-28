<template>
    <AdminLayout>
        <div class="p-4 pb-12">
            <form @submit.prevent="submit">
                <div class="input-wrap mb-6 rounded">
                    <div class="input-group w-full">
                        <label class="text-gray-800 text-lg">Content<span class="text-red-600">*</span></label>
                        <Editor class="tinyEditor" name="pageContent" v-model="form.pageContent"
                                api-key="f0nepqqrlzipwloln88xx5ltuz5ffy9trzchxjkgphxa2u4y"
                                :plugins="tinyPlugins"
                                :toolbar="tinyToolbar"
                                :init="tinyInit"/>
                        <p class="text-red-500" v-if="errors.pageContent">{{ errors.pageContent }}</p>
                    </div>
                </div>
                <button type="submit" class="py-2 px-8 rounded bg-green-500 text-white">{{ privacyPolicy ? privacyPolicy.length != 0 ? 'Update' : 'Submit' : 'Submit' }}</button>
            </form>

        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "../../Layouts/AdminLayout";
import {useForm} from "@inertiajs/inertia-vue3";
import Editor from '@tinymce/tinymce-vue';
import {onMounted, ref} from "vue";
const props = defineProps({
    privacyPolicy: Object,
    errors: Object
})

const tinyPlugins = 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons';
const tinyToolbar = 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl';
let tinyInit = {
    height: 800,
    image_class_list: [
        {title: 'img-responsive', value: 'img-responsive'},
    ],
    deprecation_warnings: false,
        content_style: "@import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap');",
        font_formats: "Naskh Arabic=Noto Naskh Arabic, serif;" ,
        fontsize_formats: "8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 40pt 48pt 52pt 60pt 72pt 96pt",

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
};

const form = useForm({
    pageContent: '',
    id: ''
});

const submit = ()=>{
    form.post(route('admin.privacy.policy.store'), {
        preserveState: false,
    })
}

onMounted(()=>{
    if (props.privacyPolicy){
        if ( props.privacyPolicy.length != 0){
            form.pageContent = props.privacyPolicy.content;
            form.id = props.privacyPolicy.id
        }
    }


})

</script>
<style scoped>
</style>
