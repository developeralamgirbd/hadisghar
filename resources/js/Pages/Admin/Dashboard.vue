<script setup>
import AdminLayout from '../../Layouts/AdminLayout';
import {Link} from "@inertiajs/inertia-vue3";

defineProps({
    total_posts: Number,
    pending_posts: Number,
    published_posts: Number,
    draft_posts: Number,
    total_user: Number,
    schedule: String
});

</script>
<template>
   <AdminLayout title="Admin Dashboard">
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                <div class="bg-[#17A2B8] rounded">
                    <div class=" flex items-center justify-between p-4">
                        <div class="">
                            <h1 class="font-bold text-white text-4xl mb-4">{{ total_posts }}</h1>
                            <span class="text-white">Total Posts</span>
                        </div>
                        <div class="">
                            <fa icon="fa-pen-to-square" class="text-[60px] text-gray-500 opacity-25"/>
                        </div>
                    </div>
                    <Link :href="route('admin.post.index')" class="text-center text-white block w-full flex-nowrap bg-[#148A9D] py-1 flex items-center justify-center gap-3">More info <fa icon="fa-arrow-right" class="text-[#148A9D] p-[2px] bg-white rounded-full h-4 w-4"/></Link>
                </div>
                <div class="bg-rose-600 rounded">
                    <div class=" flex items-center justify-between p-4">
                        <div class="">
                            <h1 class="font-bold text-white text-4xl mb-4">{{ pending_posts }}</h1>
                            <span class="text-white">Pending Posts</span>
                        </div>
                        <div class="">
                            <fa icon="fa-pen-to-square" class="text-[60px] text-gray-500 opacity-25"/>
                        </div>
                    </div>
                    <Link :href="route('admin.post.index')"  class="text-center text-white block w-full flex-nowrap bg-rose-700 py-1 flex items-center justify-center gap-3">More info <fa icon="fa-arrow-right" class="text-rose-700 p-[2px] bg-white rounded-full h-4 w-4"/></Link>
                </div>
                <div class="bg-green-600 rounded">
                    <div class=" flex items-center justify-between p-4">
                        <div class="">
                            <h1 class="font-bold text-white text-4xl mb-4">{{ published_posts }}</h1>
                            <span class="text-white">Published Posts</span>
                        </div>
                        <div class="">
                            <fa icon="fa-pen-to-square" class="text-[60px] text-gray-500 opacity-25"/>
                        </div>
                    </div>
                    <Link :href="route('admin.post.index')"  class="text-center text-white block w-full flex-nowrap bg-green-700 py-1 flex items-center justify-center gap-3">More info <fa icon="fa-arrow-right" class="text-green-700 p-[2px] bg-white rounded-full h-4 w-4"/></Link>
                </div>
                <div class="bg-gray-400 rounded">
                    <div class=" flex items-center justify-between p-4">
                        <div class="">
                            <h1 class="font-bold text-white text-4xl mb-4">{{ draft_posts }}</h1>
                            <span class="text-white">Draft Posts</span>
                        </div>
                        <div class="">
                            <fa icon="fa-pen-to-square" class="text-[60px] text-gray-500 opacity-25"/>
                        </div>
                    </div>
                    <Link :href="route('admin.post.index')"  class="text-center text-white block w-full flex-nowrap bg-gray-500 py-1 flex items-center justify-center gap-3">More info <fa icon="fa-arrow-right" class="text-gray-500 p-[2px] bg-white rounded-full h-4 w-4"/></Link>
                </div>
                <div class="bg-amber-400 rounded">
                    <div class=" flex items-center justify-between p-4">
                        <div class="">
                            <h1 class="font-bold text-white text-4xl mb-4">{{ total_user }}</h1>
                            <span class="text-white">User Registrations</span>
                        </div>
                        <div class="">
                            <fa icon="fa-user-plus" class="text-[60px] text-gray-500 opacity-25"/>
                        </div>
                    </div>
                    <Link :href="route('admin.user.index')"  class="text-center text-white block w-full flex-nowrap bg-amber-500 py-1 flex items-center justify-center gap-3">More info <fa icon="fa-arrow-right" class="text-amber-500 p-[2px] bg-white rounded-full h-4 w-4"/></Link>
                </div>
            </div>
            <!-- Schedule-->
            <div class="w-full">

            </div>
        </div>
   </AdminLayout>

</template>

