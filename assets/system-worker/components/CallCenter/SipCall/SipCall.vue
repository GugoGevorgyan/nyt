<!-- @format -->

<template>
    <div>
        <audio id="remoteAudio" autoplay hidden />
        <div v-if="phoneShow" style="position: absolute; z-index: 9; right: 10px; bottom: 75px">
            <v-img style="overflow: hidden" height="700" src="/storage/img/phone/phone.png" />
            <div style="position: absolute; top: 45px; right: 3px; height: 618px; width: 310px">
                <v-img style="z-index: -1" height="100%" src="/storage/img/phone/wallpapper.jpg" />
                <div style="position: absolute; top: 0; right: 0; height: 578px; width: 100%">
                    <v-stepper style="background: transparent; height: 100%" v-model="phoneActivePage">
                        <v-stepper-items>
                            <!--home-->

                            <v-stepper-content key="home" step="home" class="pa-0">
                                <div style="height: 578px; position: relative" class="pa-2">
                                    <div class="d-flex justify-center" style="height: 520px; align-items: center">
                                        <div>
                                            <span
                                                class="font-weight-thin"
                                                style="font-size: 54px; color: white"
                                                v-text="phoneClock"
                                            />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-space-between px-4">
                                        <v-btn
                                            large
                                            icon
                                            color="white"
                                            @click="[(phoneActivePage = 'calls'), (phoneLastPage = 'home')]"
                                        >
                                            <v-icon large v-text="'mdi-phone'" />
                                        </v-btn>
                                        <v-btn
                                            large
                                            icon
                                            color="white"
                                            @click="[(phoneActivePage = 'messages'), (phoneLastPage = 'home')]"
                                        >
                                            <v-icon large v-text="'mdi-message'" />
                                        </v-btn>
                                    </div>
                                </div>
                            </v-stepper-content>

                            <!--calls-->

                            <v-stepper-content key="calls" step="calls" class="pa-0">
                                <div style="height: 578px; position: relative" class="pa-2">
                                    <div style="height: 100%; background: white; border-radius: 5px">
                                        <p class="display-1 font-weight-thin pt-7 text-center">Calls</p>
                                        <div class="" style="overflow-y: auto; height: 475px">
                                            <div style="padding-bottom: 90px">
                                                <v-list tree-line subheader height="100%">
                                                    <v-list-item
                                                        two-line
                                                        v-for="item in phoneCalls"
                                                        :key="item.phone"
                                                        class="px-1"
                                                    >
                                                        <v-list-item-content>
                                                            <v-list-item-title
                                                                @click="sipCall(item[0].client_phone)"
                                                                class="v-btn font-weight-regular px-1"
                                                                style="border-radius: 5px; font-size: 14px"
                                                            >
                                                                <div class="d-flex" style="width: 100%">
                                                                    <v-icon small class="mx-1" color="success"
                                                                        >mdi-phone</v-icon
                                                                    >
                                                                    <span>{{ item[0].client_phone }}</span>
                                                                    <v-spacer></v-spacer>
                                                                    <span v-if="item.length > 1"
                                                                        >({{ item.length }})</span
                                                                    >
                                                                    <v-icon
                                                                        small
                                                                        v-else
                                                                        :color="
                                                                            item[0].incoming && item[0].answered
                                                                                ? 'primary'
                                                                                : item[0].incoming
                                                                                ? 'error'
                                                                                : 'green'
                                                                        "
                                                                    >
                                                                        {{
                                                                            item[0].incoming && item[0].answered
                                                                                ? "mdi-call-received"
                                                                                : item[0].incoming
                                                                                ? "mdi-call-missed"
                                                                                : "mdi-call-made"
                                                                        }}
                                                                    </v-icon>
                                                                </div>
                                                            </v-list-item-title>
                                                            <v-list-item-subtitle
                                                                @click="
                                                                    (phoneInfoNumber = item[0].client_phone),
                                                                        (phoneActivePage = 'callInfo'),
                                                                        (phoneLastPage = 'calls')
                                                                "
                                                                class="v-btn px-1"
                                                                style="border-radius: 5px; font-size: 12px"
                                                            >
                                                                <div class="d-flex" style="width: 100%">
                                                                    <v-icon small class="mx-1" color="primary">
                                                                        mdi-information-outline
                                                                    </v-icon>
                                                                    <span class="text-truncate">{{
                                                                        item[0].client_phone
                                                                    }}</span>
                                                                    <v-spacer></v-spacer>
                                                                    <span>{{ item[0].time_ago }}</span>
                                                                </div>
                                                            </v-list-item-subtitle>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </v-list>
                                            </div>
                                        </div>
                                        <div
                                            style="
                                                position: absolute;
                                                bottom: 0;
                                                right: 50%;
                                                transform: translate(50%, -50%);
                                            "
                                        >
                                            <v-btn
                                                dark
                                                fab
                                                color="pink"
                                                @click="(phoneActivePage = 'phone'), (phoneLastPage = 'calls')"
                                            >
                                                <v-icon>mdi-dialpad</v-icon>
                                            </v-btn>
                                        </div>
                                    </div>
                                </div>
                            </v-stepper-content>

                            <!--call info-->

                            <v-stepper-content key="callInfo" step="callInfo" class="pa-0">
                                <div style="height: 578px; position: relative" class="pa-2">
                                    <div
                                        v-if="phoneInfoCalls"
                                        style="height: 100%; background: white; border-radius: 5px"
                                    >
                                        <div class="display-1 font-weight-thin pt-7">
                                            <p class="text-center">{{ phoneInfoNumber }}</p>
                                            <p class="text-center">Calls info</p>
                                        </div>
                                        <p class="title font-weight-regular text-center">
                                            {{ phoneInfoCalls[0].client_phone }}
                                        </p>
                                        <div class="d-flex align-center px-3">
                                            <v-btn icon @click="sipCall(phoneInfoNumber)">
                                                <v-icon color="success">mdi-phone</v-icon>
                                            </v-btn>
                                            <span>Call Back</span>
                                        </div>
                                        <div class="" style="overflow-y: auto; height: 352px">
                                            <v-list tree-line subheader height="100%">
                                                <v-list-item
                                                    @click="sipCall(item.client_phone)"
                                                    two-line
                                                    v-for="item in phoneInfoCalls"
                                                    :key="item.client_call_id"
                                                >
                                                    <v-list-item-content>
                                                        <v-list-item-title class="font-weight-regular">
                                                            <div class="d-flex" style="width: 100%">
                                                                <span
                                                                    >{{ item.call_date }}
                                                                    {{ item.client_call_id }}</span
                                                                >
                                                                <v-spacer></v-spacer>
                                                                <v-icon
                                                                    small
                                                                    :color="
                                                                        item.incoming && item.answered
                                                                            ? 'primary'
                                                                            : item.incoming
                                                                            ? 'error'
                                                                            : 'green'
                                                                    "
                                                                >
                                                                    {{
                                                                        item.incoming && item.answered
                                                                            ? "mdi-call-received"
                                                                            : item.incoming
                                                                            ? "mdi-call-missed"
                                                                            : "mdi-call-made"
                                                                    }}
                                                                </v-icon>
                                                            </div>
                                                        </v-list-item-title>
                                                        <v-list-item-subtitle>
                                                            <div class="d-flex" style="width: 100%">
                                                                <span class="text-truncate">{{ item.call_time }}</span>
                                                                <v-spacer></v-spacer>
                                                                <span>{{ item.duration_time }}</span>
                                                            </div>
                                                        </v-list-item-subtitle>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-list>
                                        </div>
                                    </div>
                                </div>
                            </v-stepper-content>

                            <!--phone-->

                            <v-stepper-content key="phone" step="phone" class="pa-0">
                                <div style="height: 578px; position: relative" class="pa-2">
                                    <div style="height: 100%; background: white; border-radius: 5px">
                                        <p class="display-1 font-weight-thin pt-7 text-center">Phone</p>
                                        <div class="d-flex justify-center align-center">
                                            <div style="width: 100%" class="pa-2">
                                                <div>
                                                    <div class="d-flex justify-space-around align-center">
                                                        <!--<span style="font-size: 22px;" class="mx-1 font-weight-light">(+{{countryCode}})</span>-->
                                                        <v-text-field
                                                            v-model="phoneCallNumber"
                                                            style="font-size: 22px"
                                                            class="centered-input font-weight-light"
                                                        ></v-text-field>
                                                        <v-btn icon @click="callPadBackspace()">
                                                            <v-icon>mdi-backspace-outline</v-icon>
                                                        </v-btn>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-space-around">
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('1')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >1</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('2')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >2</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('3')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >3</v-btn
                                                        >
                                                    </div>
                                                    <div class="d-flex justify-space-around">
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('4')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >4</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('5')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >5</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('6')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >6</v-btn
                                                        >
                                                    </div>
                                                    <div class="d-flex justify-space-around">
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('7')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >7</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('8')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >8</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('9')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >9</v-btn
                                                        >
                                                    </div>
                                                    <div class="d-flex justify-space-around">
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('*')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >*</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('0')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >0</v-btn
                                                        >
                                                        <v-btn
                                                            fab
                                                            text
                                                            @click="callPadBtn('#')"
                                                            color="grey"
                                                            class="font-weight-thin"
                                                            style="font-size: 40px"
                                                            >#</v-btn
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        style="
                                            position: absolute;
                                            bottom: 0;
                                            right: 50%;
                                            transform: translate(50%, -50%);
                                        "
                                    >
                                        <v-btn dark fab color="primary" @click="sipCall(callNumberWithCode)">
                                            <v-icon>mdi-phone</v-icon>
                                        </v-btn>
                                    </div>
                                </div>
                            </v-stepper-content>

                            <!--messages-->

                            <v-stepper-content key="messages" step="messages" class="pa-0">
                                <div style="height: 578px">
                                    <div class="d-flex justify-center" style="height: 550px; align-items: center">
                                        <div><span style="font-size: 54px; color: white">Messages</span></div>
                                    </div>
                                </div>
                            </v-stepper-content>
                        </v-stepper-items>
                    </v-stepper>

                    <!--call-->

                    <v-overlay
                        absolute
                        opacity="0.95"
                        v-model="callOverlay"
                        style="
                            width: 294px;
                            border-radius: 20px 20px 6px 6px;
                            left: 50%;
                            top: 0;
                            transform: translate(-50%, 0px);
                        "
                        class="column"
                    >
                        <div>
                            <p style="font-size: 32px; color: white" class="text-center">{{ callObj.number }}</p>
                        </div>
                        <div v-if="!callObj.answered" class="d-flex justify-center">
                            <v-btn
                                v-if="callObj.incoming"
                                large
                                icon
                                color="success"
                                class="ma-3"
                                @click="acceptCall()"
                            >
                                <v-icon x-large v-text="'mdi-phone-in-talk'" />
                            </v-btn>
                            <v-btn large icon color="error" class="ma-3" @click="terminateCall()">
                                <v-icon x-large v-text="'mdi-phone-off'" />
                            </v-btn>
                        </div>
                        <div v-else class="d-flex align-center flex-column">
                            <span class="error--text">{{ callObj.time }}</span>
                            <v-btn large icon color="error" class="ma-2" @click="terminateCall()">
                                <v-icon x-large v-text="'mdi-phone-off'" />
                            </v-btn>
                        </div>
                    </v-overlay>
                </div>
                <div
                    class="d-flex justify-space-around align-center"
                    style="position: absolute; bottom: 0; width: 100%; height: 40px"
                >
                    <v-btn icon color="white" @click="phoneActivePage = phoneLastPage">
                        <v-icon v-text="'mdi-backburger'" />
                    </v-btn>
                    <v-btn icon color="white" @click="phoneActivePage = 'home'">
                        <v-icon v-text="'mdi-home'" />
                    </v-btn>
                </div>
                <v-img
                    style="z-index: -1; position: absolute; bottom: 0"
                    height="40px"
                    width="100%"
                    src="/storage/img/phone/bottom-wallpapper.jpg"
                />
            </div>
        </div>
    </div>
</template>

<script lang="js" src="./SipCall.main.js" />
<style scoped lang="scss" src="./SipCall.style.scss" />
