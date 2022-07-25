<template>
    <AdminLayout>
            <div class="p-6 pb-10">
                <div class="shadow bg-white border p-4 rounded">
                    <h1 class="text-2xl text-green-700 mb-6">Visitor Lockout</h1>
                    <div class="bg-blue-50 border border-sky-800 rounded p-3">
                        <p class="mb-2 text-sky-800">This feature allows you to put your site into "maintenance mode" by locking down the front-end to all visitors except logged in users with super admin privileges.</p>
                        <br>
                        <p class="text-sky-800 pt-4">Locking your site down to general visitors can be useful if you are investigating some issues on your site or perhaps you might be doing some maintenance and wish to keep out all traffic for security reasons.</p>
                    </div>
                    <div class="mt-6 flex gap-4 items-center">
                        <p>Enable/Disable Front-end Lockout</p>
                        <button @click="powerBtn" class="text-4xl" :class="power ? 'text-green-700' : 'text-red-700'"><fa icon="fa-power-off" class="drop-shadow-lg shadow-blue-600/50"/></button>
                    </div>
                </div>
            </div>
    </AdminLayout>
</template>

<script setup>
    import AdminLayout from "../../../Layouts/AdminLayout";
    import {onMounted, ref} from "vue";
    import {Inertia} from "@inertiajs/inertia";

  const props = defineProps({
      maintenance_mode: Object
   });
    const power = ref(true)

    const powerBtn = ()=>{
        power.value = !power.value;
        if (power.value){
            Inertia.get(route('admin.maintenance.setting', 'on'))
        }else {
            Inertia.get(route('admin.maintenance.setting', 'off'))
        }
    }
    onMounted(()=>{
        if(props.maintenance_mode.mode == 'on'){
            power.value = true;
        }else if(props.maintenance_mode.mode == 'off') {
            power.value = false;
        }

    })
</script>

<style scoped>

</style>
