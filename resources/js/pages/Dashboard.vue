<template>
    <div class="px-9 pt-2">
        <!-- dashboard -->
        <div class="flex justify-between items-center">
            <h1 class="page-title">Dashboard</h1>
        </div>
        <!-- add line -->
        <hr class="mt-4 mb-6 border-main">
        <!-- ui start -->
        <div class="flex flex-wrap">
            <div class="flex-0 pr-2">
                <!-- quiz container -->
                <div class="bg-white border rounded-lg shadow-lg mb-6">
                    <div class="flex items-center px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M14 15q.425 0 .738-.312t.312-.738q0-.425-.312-.737T14 12.9q-.425 0-.737.313t-.313.737q0 .425.313.738T14 15m-.75-3.2h1.5q0-.725.15-1.062t.7-.888q.75-.75 1-1.212t.25-1.088q0-1.125-.788-1.837T14 5q-1.025 0-1.787.575T11.15 7.1l1.35.55q.225-.625.613-.937T14 6.4q.6 0 .975.338t.375.912q0 .35-.2.663t-.7.787q-.825.725-1.012 1.138T13.25 11.8M8 18q-.825 0-1.412-.587T6 16V4q0-.825.588-1.412T8 2h12q.825 0 1.413.588T22 4v12q0 .825-.587 1.413T20 18zm-4 4q-.825 0-1.412-.587T2 20V6h2v14h14v2z"/></svg>
                        <h2 class="ml-2 text-lg font-semibold box-label"></h2>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-4 pb-4">
                        <!-- categories box -->
                        <router-link to="/categories" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">CATEGORIES</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ categories }}</h5>
                            </div>
                        </router-link>
                      <!-- problem -->
                        <router-link to="/problems" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">PROBLEMS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ problems }}</h5>
                            </div>
                        </router-link>
                        <!-- problem options -->
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">PROBLEM OPTIONS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ problemOptions }}</h5>
                            </div>

                    </div>
                </div>
                 <!-- user container -->
                 <div class="bg-white border rounded-lg shadow-lg mb-6">
                    <div class="flex items-center px-4 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512"><g transform="translate(640 0) scale(-1 1)"><path fill="currentColor" d="M144 0a80 80 0 1 1 0 160a80 80 0 1 1 0-160m368 0a80 80 0 1 1 0 160a80 80 0 1 1 0-160M0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96H21.3C9.6 320 0 310.4 0 298.7M405.3 320h-.7c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7c58.9 0 106.7 47.8 106.7 106.7c0 11.8-9.6 21.3-21.3 21.3zM224 224a96 96 0 1 1 192 0a96 96 0 1 1-192 0m-96 261.3c0-73.6 59.7-133.3 133.3-133.3h117.4c73.6 0 133.3 59.7 133.3 133.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7"/></g></svg>
                        <h2 class="ml-2 text-lg font-semibold box-label"></h2>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-4 pb-4">
                         <!-- admin box -->
                         <router-link to="/admins" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">ADMINS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ admins }}</h5>
                            </div>
                        </router-link>
                        <!-- user box -->
                        <router-link to="/categories" class="link-text">
                            <div class="bg-soft hover:bg-main-hover border rounded-lg shadow-lg text-center">
                                <h5 class="my-3 label">USERS</h5>
                                <hr class="border-main">
                                <h5 class="my-3 label-count">{{ users }}</h5>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['categories', 'problems', 'problem-options','users','admins'],
}

</script>
<style scoped>
.page-title {
    font-size: 34px;
    font-style: normal;
    font-weight: 800;
    line-height: normal;
    text-transform: uppercase;
    color: #000000;
}

.box-label {
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    color: #000000;
}

.link-text {
    color: black;
}

.label {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 22px; /* 137.5% */
    text-transform: uppercase;
}
.label-count {
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}
</style>
