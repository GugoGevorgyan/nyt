<!-- @format -->

<template>
    <v-container fluid>
        <v-row>
            <v-col class="pr-0" cols="12" xs="5" sm="4" md="2" lg="2" xl="2">
                <v-card
                    class="py-6 px-4 top-styling"
                    tile
                    :style="{ height: height + 37 + 'px' }"
                    style="overflow: auto"
                    elevation="6"
                >
                    <v-img :src="user.photo || noImage" class="mb-2" />
                    <h2 class="font-weight-regular" style="color: #757575; font-size: 22px">
                        {{ user.name }} {{ user.surname }} {{ user.patronymic }}
                    </h2>
                    <template v-if="!isHome">
                        <div class="mb-2">
                            <v-btn @click="newComplaintDialog = true" text color="error" style="width: 100%">
                                <v-icon class="mr-1" v-text="'mdi-alert'" />
                                <span>пожаловаться</span>
                            </v-btn>
                        </div>
                        <v-divider class="mb-2" />
                    </template>
                    <div class="mb-2" v-if="user.email || user.phone">
                        <div class="d-flex" v-if="user.email">
                            <v-icon small class="mr-2" color="yellow darken-3" v-text="'mdi-mail'" />
                            <span>{{ user.email }}</span>
                        </div>
                        <div class="d-flex" v-if="user.phone">
                            <v-icon small class="mr-2" color="yellow darken-3" v-text="'mdi-phone'" />
                            <span>{{ user.phone }}</span>
                        </div>
                    </div>
                    <v-divider class="mb-2"></v-divider>
                    <div>
                        <div class="subtitle-1">Роли</div>
                        <div v-for="item in user.roles" :key="item.role_id" style="color: #757575; font-size: 12px">
                            {{ item.text }}
                        </div>
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" xs="7" sm="8" md="10" lg="10" xl="10">
                <template v-if="isHome">
                    <v-card class="py-0 top-styling" tile elevation="6">
                        <v-tabs v-model="tab" color="yellow darken-3" height="35" :show-arrows="false">
                            <v-tab :key="0" class="font-weight-light">
                                <span>Персональная информация</span>
                            </v-tab>
                            <v-tab :key="1" class="font-weight-light">
                                <span>Жалобы</span>
                            </v-tab>
                        </v-tabs>
                        <v-tabs-items v-model="tab">
                            <v-tab-item :key="0">
                                <div class="pa-2" :style="{ height: height + 'px' }" style="overflow: auto">
                                    <v-container style="width: 800px; padding-top: 150px">
                                        <v-form>
                                            <v-subheader>Основная информация</v-subheader>
                                            <v-divider class="mt-0 mb-6" />
                                            <v-row>
                                                <v-col cols="12" md="8">
                                                    <v-row>
                                                        <v-col cols="12" md="6" class="pr-2">
                                                            <v-text-field
                                                                label="Фамилия"
                                                                outlined
                                                                dense
                                                                :error-messages="errors.collect('surname')"
                                                                name="surname"
                                                                v-model="worker.surname"
                                                                v-validate="worker.rules.surname"
                                                                data-vv-as="фамилия"
                                                            />
                                                            <v-text-field
                                                                label="Имя"
                                                                outlined
                                                                dense
                                                                :error-messages="errors.collect('name')"
                                                                name="name"
                                                                v-model="worker.name"
                                                                v-validate="worker.rules.name"
                                                                data-vv-as="имя"
                                                            />
                                                            <v-text-field
                                                                label="Отчество"
                                                                outlined
                                                                dense
                                                                :error-messages="errors.collect('patronymic')"
                                                                name="patronymic"
                                                                v-model="worker.patronymic"
                                                                v-validate="worker.rules.patronymic"
                                                                data-vv-as="отчество"
                                                            />
                                                        </v-col>
                                                        <v-col cols="12" md="6" class="pl-2">
                                                            <v-text-field
                                                                :loading="emailLoading"
                                                                outlined
                                                                dense
                                                                :error-messages="errors.collect('email')"
                                                                :persistent-hint="false"
                                                                append-icon="mdi-email"
                                                                label="Эл. почта"
                                                                name="email"
                                                                v-model="worker.email"
                                                                v-validate="worker.rules.email"
                                                                data-vv-as="эл. почта"
                                                                clearable
                                                            />
                                                            <v-text-field
                                                                label="Телефон"
                                                                outlined
                                                                dense
                                                                :error-messages="errors.collect('phone')"
                                                                append-icon="mdi-phone"
                                                                name="phone"
                                                                v-mask="phoneMask"
                                                                v-model="worker.phone"
                                                                v-validate="worker.rules.phone"
                                                                data-vv-as="телефон"
                                                                clearable
                                                            />
                                                        </v-col>
                                                    </v-row>
                                                </v-col>
                                                <v-col cols="12" md="4" class="d-flex justify-end align-center mb-4">
                                                    <div style="width: 200px">
                                                        <div
                                                            style="overflow: hidden; height: 200px; width: 200px"
                                                            class="d-flex justify-center align-center elevation-1 mb-2"
                                                        >
                                                            <v-img
                                                                aspect-ratio="0"
                                                                style="cursor: pointer"
                                                                :src="worker.photo ? worker.photo : noImage"
                                                                @click="$refs.photoInput.click()"
                                                                width="200"
                                                                height="200"
                                                            />
                                                        </div>
                                                        <small class="red--text" v-if="errors.first('photo_file')">
                                                            {{ errors.first("photo_file") }}
                                                        </small>
                                                    </div>

                                                    <input
                                                        ref="photoInput"
                                                        class="d-none"
                                                        type="file"
                                                        accept="image/*"
                                                        @change="previewImage($event, 'photo')"
                                                        name="photo_file"
                                                        v-validate="worker.rules.photo_file"
                                                        data-vv-as="фотография"
                                                    />
                                                </v-col>
                                            </v-row>

                                            <v-subheader>Данные входа в систему</v-subheader>
                                            <v-divider class="mt-0 mb-6" />
                                            <v-row>
                                                <v-col cols="12" md="4">
                                                    <v-text-field
                                                        :loading="nicknameLoading"
                                                        label="Ник"
                                                        outlined
                                                        dense
                                                        :error-messages="errors.collect('nickname')"
                                                        :persistent-hint="false"
                                                        name="nickname"
                                                        v-model="worker.nickname"
                                                        v-validate="worker.rules.nickname"
                                                        data-vv-as="ник"
                                                    />
                                                </v-col>
                                                <v-col cols="12" md="4" class="d-flex justify-end">
                                                    <v-switch class="mt-0" v-model="worker.change_password"></v-switch>
                                                    <span class="mt-1">Изменить пароль</span>
                                                </v-col>
                                                <v-col cols="12" md="4" v-show="worker.change_password">
                                                    <div class="d-flex">
                                                        <v-text-field
                                                            outlined
                                                            dense
                                                            :error-messages="errors.collect('password')"
                                                            :persistent-hint="false"
                                                            append-icon="mdi-lock-question"
                                                            label="Пароль"
                                                            name="password"
                                                            v-model="worker.password"
                                                            v-validate="
                                                                worker.change_password ? worker.rules.password : null
                                                            "
                                                            data-vv-as="пароль"
                                                            ref="password"
                                                            class="mr-2"
                                                        />
                                                        <v-tooltip right>
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-btn
                                                                    v-on="on"
                                                                    @click="generatePassword"
                                                                    color="primary"
                                                                    fab
                                                                    small
                                                                >
                                                                    <v-icon>mdi-hammer-screwdriver</v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Сгенерерировать пароль</span>
                                                        </v-tooltip>
                                                    </div>
                                                </v-col>
                                            </v-row>
                                            <v-divider class="mt-0 mb-6" />
                                            <div class="d-flex justify-end">
                                                <v-btn
                                                    class="mr-2"
                                                    :disabled="!infoChanged || infoUpdateLoading"
                                                    color="error"
                                                    outlined
                                                    small
                                                    @click="cancelUpdateInfo()"
                                                    >отменить изменения</v-btn
                                                >
                                                <v-btn
                                                    :disabled="!infoChanged"
                                                    color="primary"
                                                    small
                                                    :loading="infoUpdateLoading"
                                                    @click="updateInfo()"
                                                    >Сохранить изменения</v-btn
                                                >
                                            </div>
                                        </v-form>
                                    </v-container>
                                </div>
                            </v-tab-item>
                            <v-tab-item :key="1">
                                <div class="pa-2" :style="{ height: height + 'px' }" style="overflow: auto">
                                    <div class="d-flex align-center mb-2 px-2">
                                        <v-tooltip left>
                                            <template v-slot:activator="{ on: on }">
                                                <v-btn
                                                    class="mr-2"
                                                    v-on="on"
                                                    color="#F9A825"
                                                    fab
                                                    x-small
                                                    @click="newComplaintDialog = true"
                                                >
                                                    <v-icon v-text="'mdi-plus'" />
                                                </v-btn>
                                            </template>
                                            <span>Составить новую жалобу</span>
                                        </v-tooltip>
                                        <v-tooltip right>
                                            <template v-slot:activator="{ on: on }">
                                                <v-btn
                                                    v-on="on"
                                                    :disabled="complaints.loading"
                                                    fab
                                                    x-small
                                                    color="#F9A825"
                                                    @click="refreshComplaints()"
                                                >
                                                    <v-icon>mdi-refresh</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Обновить список жалоб</span>
                                        </v-tooltip>
                                    </div>
                                    <v-divider class="my-0" />
                                    <div
                                        style="overflow-y: auto"
                                        :style="{ height: complaintsHeight + 'px' }"
                                        class="pa-2"
                                    >
                                        <div
                                            v-if="complaints.loading"
                                            class="d-flex justify-center align-center"
                                            style="width: 100%; height: 100%"
                                        >
                                            <v-progress-circular indeterminate color="#F9A825" :width="2" :size="50" />
                                        </div>
                                        <template v-else-if="complaints.data.length">
                                            <div
                                                v-for="(item, index) in complaints.data"
                                                :key="item.complaint_id"
                                                class="mb-4"
                                            >
                                                <div class="py-2 d-flex justify-space-between">
                                                    <div>
                                                        <div class="d-flex align-center">
                                                            <v-tooltip left>
                                                                <template v-slot:activator="{ on: on }">
                                                                    <v-icon
                                                                        v-on="on"
                                                                        class="mr-2"
                                                                        :color="
                                                                            item.recipient_id === user.system_worker_id
                                                                                ? 'error'
                                                                                : 'success'
                                                                        "
                                                                    >
                                                                        mdi-alert
                                                                    </v-icon>
                                                                </template>
                                                                <span>{{
                                                                    item.recipient_id === user.system_worker_id
                                                                        ? "Жалоба на Вас"
                                                                        : "Жалоба от Вас"
                                                                }}</span>
                                                            </v-tooltip>
                                                            <span style="color: #616161; font-size: 14px">{{
                                                                item.subject
                                                            }}</span>
                                                        </div>
                                                        <div class="d-flex align-center">
                                                            <span class="mr-1 font-weight-light">
                                                                {{
                                                                    item.recipient_id === user.system_worker_id
                                                                        ? `жалоба от`
                                                                        : `жалоба на`
                                                                }}
                                                            </span>
                                                            <!--<a
                                                                target="_blank"
                                                                :href="
                                                            $router.resolve({
                                                                name: 'profile_view',
                                                                params: {
                                                                    system_worker_id: item.recipient_id === user.system_worker_id?
                                                                    item.writer.system_worker_id: item.recipient.system_worker_id
                                                                    },
                                                            }).href
                                                        ">-->
                                                            <span style="color: #f9a825">
                                                                {{
                                                                    item.recipient_id === user.system_worker_id
                                                                        ? `${item.writer.name} ${item.writer.patronymic} ${item.writer.surname}`
                                                                        : `${item.recipient.name} ${item.recipient.patronymic} ${item.recipient.surname}`
                                                                }}
                                                            </span>
                                                            <!--</a>-->
                                                            <v-chip
                                                                class="mx-2"
                                                                x-small
                                                                outlined
                                                                :color="item.status.color"
                                                            >
                                                                {{ item.status.text }}
                                                            </v-chip>
                                                        </div>
                                                    </div>
                                                    <v-btn
                                                        outlined
                                                        x-small
                                                        color="#424242"
                                                        @click="showComplaint(item)"
                                                    >
                                                        <v-icon x-small class="mr-1">mdi-eye</v-icon>
                                                        показать
                                                    </v-btn>
                                                </div>
                                                <v-divider v-if="index < complaints.data.length - 1" />
                                            </div>
                                        </template>
                                        <div
                                            v-else
                                            class="d-flex justify-center align-center"
                                            style="height: 100%; width: 100%"
                                        >
                                            <span>нет жалоб</span>
                                        </div>
                                    </div>
                                    <v-divider class="my-0" />
                                    <div class="d-flex justify-center">
                                        <v-pagination
                                            :length="complaints.last_page"
                                            :total-visible="7"
                                            circle
                                            color="yellow darken-3"
                                            v-model="complaints.current_page"
                                        />
                                    </div>
                                </div>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-card>
                </template>
            </v-col>
        </v-row>

        <template v-if="isHome">
            <v-dialog v-model="complaintDialog" width="1400" persistent style="overflow: hidden">
                <complaint-dialog
                    v-if="viewComplaint"
                    :complaint="viewComplaint"
                    :manager="false"
                    :statuses="[]"
                    @close="closeComplaint()"
                />
            </v-dialog>
        </template>
        <v-dialog v-model="newComplaintDialog" width="800" persistent>
            <complaint-form
                :worker="isHome ? null : user"
                @created="isHome ? refreshComplaints() : null"
                @close="newComplaintDialog = false"
            />
        </v-dialog>
    </v-container>
</template>

<script lang="vue" src="./profile.main.js" />
<style lang="scss" scoped src="./profile.style.scss" />
