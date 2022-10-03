/** @format */

export const orderMapObjects = {
    methods: {
        driverPlaceMark(self, driver) {
            return (
                `${
                    self.driverIsPending(driver) ? self.loading() : ""
                }<div style="height: 25px; width: 25px; background-color: transparent; border-radius: 50%; border: 2px solid ` +
                driver.status.color +
                `; display: flex; justify-content: center; align-items: center; box-shadow: 0 8px 11px -5px rgba(0,0,0,.2),0 17px 26px 2px rgba(0,0,0,.14),0 6px 32px 5px rgba(0,0,0,.12)!important;">
                           <img style="height: 20px; width: 20px;" src="/storage/img/taxi/car.svg">
                        </div>`
            );
        },

        driverBalloon(self, driver) {
            return `<div>
                            <div style="margin-bottom: 3px">
                                <span>водитель: ${driver.driver_info.name} ${driver.driver_info.surname}</span>
                            </div>
                            <div style="margin-bottom: 3px">
                                <span>телефон: ${driver.phone}</span>
                                <!--suppress CssUnknownTarget -->
                                    <span
                                        id="driver-phone-${driver.driver_id}"
                                        style="
                                        margin-left:2px;
                                        cursor: pointer;
                                        transition: 0.3s;
                                        filter: invert(56%) sepia(60%) saturate(3017%) hue-rotate(134deg) brightness(96%) contrast(102%);
                                        background-size: cover;
                                        display: inline-block;
                                        height: 13px;
                                        background-image: url(/storage/img/phone.svg);
                                        width: 13px;"
                                    />
                            </div>
                            <div style="margin-bottom: 7px">
                                <span>автомобиль:</span>
                                <span>${driver.car.mark} ${driver.car.model}</span></span>
                            </div>
                            <div style="margin-bottom: 7px">
                                <span>класс:</span>
                                <span>${self.commaJoin(driver.car.classes, "class_name")}</span></span>
                            </div>
                            <div style="margin-bottom: 7px">
                                ${
                                    self.driverIsPending(driver)
                                        ? self.driverStatusLoading(self)
                                        : self.driverStatus(driver)
                                }
                            </div>
                            <div>${self.driverAttachBtn(self, driver)}</div>
                            <style>
                                .attach{
                                    padding: 3px; border: 1px solid #0091EA; color: #FFFFFF; background-color: #0091EA; transition: 0.2s
                                }
                                .attach:hover{
                                    color:  #0091EA; background-color: #FFFFFF;
                                }
                                .attach-danger{
                                    padding: 3px; border: 1px solid #8c1b00; color: #565656; background-color: #ea6d00; transition: 0.2s
                                }
                                .attach-danger:hover{
                                    color:  #ea6d00; background-color: #FFFFFF;
                                }
                            </style>
                        </div>`;
        },

        createDriverPlaceMark(driver) {
            let self = this;
            let balloonContentLayout = ymaps.templateLayoutFactory.createClass(self.driverBalloon(self, driver), {
                build: function (event) {
                    balloonContentLayout.superclass.build.call(this);
                    self.driverPlaceMarkCallAction(self, driver);
                    if (!self.driverIsPending(driver)) {
                        self.placeMarkOptions.driver.attach ? self.driverPlaceMarkAttachAction(self, driver) : null;
                    }
                    if (self.driverIsAcceptPending(driver)) {
                        self.unpinOrderToDriver(self, driver.driver_id);
                    }
                },
                clear: function () {
                    balloonContentLayout.superclass.clear.call(this);
                },
            });
            let layout = ymaps.templateLayoutFactory.createClass(self.driverPlaceMark(self, driver));
            let placeMark = new ymaps.Placemark(
                [driver.lat, driver.lut],
                {
                    hintContent:
                        driver.driver_info.surname +
                        " " +
                        driver.driver_info.name +
                        " " +
                        driver.driver_info.patronymic,
                },
                {
                    balloonContentLayout: balloonContentLayout,
                    iconLayout: layout,
                    iconShape: {
                        type: "Circle",
                        coordinates: [12, 12],
                        radius: 25,
                    },
                },
            );

            placeMark.events.add(["click"], function (event) {
                // @TODO add road attached order
                // if (self.placeMarkOptions.driver.attach && self.placeMarkOptions.driver.attachOrder) {
                //     self.setMapRoute([
                //         [driver.lat, driver.lut],
                //         [
                //             self.placeMarkOptions.driver.attachOrder.from_coordinates.lat,
                //             self.placeMarkOptions.driver.attachOrder.from_coordinates.lut,
                //         ],
                //     ]);
                // }
            });

            return placeMark;
        },

        driverAttachBtn(self, driver) {
            return self.placeMarkOptions.driver.attach && !self.driverIsPending(driver)
                ? `<button id="driver-attach-${driver.driver_id}" class="attach">Прикрепить</button>`
                : "";
        },

        loading() {
            return `<div style="display: flex; width: 40px; margin-bottom: 7px; margin-left: -2px">
                                  <div class="dot dot-1"></div>
                                  <div class="dot dot-2"></div>
                                  <div class="dot dot-3"></div>
                                </div>
                        <style>
                                   .dot {
                                      width: 7px;
                                      height: 7px;
                                      margin: 0 auto;
                                      border-radius: 50%;
                                      margin-right: .4em;
                                      animation-name: dots;
                                      animation-duration: 1s;
                                      animation-iteration-count: infinite;
                                      animation-timing-function: ease-in-out;
                                    }

                                    @keyframes dots {
                                      50% {
                                        opacity: 0;
                                        transform: scale(0.7) translateY(10px);
                                      }
                                    }

                                    .dot-1 {
                                      background-color: #4285F4;
                                      animation-delay: 0.1s;
                                    }

                                    .dot-2 {
                                      background-color: #DB4437;
                                      animation-delay: 0.2s;
                                    }

                                    .dot-3 {
                                      background-color: #d7a000;
                                      animation-delay: 0.3s;
                                    }
                                </style>`;
        },

        driverStatus(driver) {
            if (this.driverIsAcceptPending(driver)) {
                return `
                    <span>статус: <span style="margin-right: 5px; height: 10px; width: 10px; background-color: ${driver.status.color};border-radius: 50%;display: inline-block;"></span>
                    <span>${driver.status.text}</span>
                    <div>
                        <hr>
                        <button id="order-driver-${driver.driver_id}" style="display: flex; align-items: center" class="mt-2 attach-danger">
                            Открепит
                        </button>
                    </div>`;
            } else {
                return `
                    <span>статус: <span style="margin-right: 5px; height: 10px; width: 10px; background-color: ${driver.status.color};border-radius: 50%;display: inline-block;"></span>
                    <span>${driver.status.text}</span>`;
            }
        },

        driverStatusLoading(self) {
            return (
                `<div style="display: flex; align-items: center">
                <div style="margin-right: 10px">` +
                self.loading() +
                `</div>
                    <span>Принимает заказ...</span>
                </div>`
            );
        },

        orderBalloon(self, order) {
            return `<div>
                        <div style="margin-bottom: 3px">
                            <span>Откуда: ${order.address_from}</span>
                        </div>
                        <div style="margin-bottom: 3px">
                            <span>Куда:  ${order.address_to || "-"}</span>
                        </div>
                        <div style="margin-bottom: 7px">
                             <span>Клиент: </span>
                             <span>${order.client.phone}</span>
                             <!--suppress CssUnknownTarget -->
                                <span
                                    id="order-phone-${order.order_id}"
                                    style="
                                    margin-left:2px;
                                    cursor: pointer;
                                    transition: 0.3s;
                                    filter: invert(56%) sepia(60%) saturate(3017%) hue-rotate(134deg) brightness(96%) contrast(102%);
                                    background-size: cover;
                                    display: inline-block;
                                    height: 15px;
                                    background-image: url(/storage/img/phone.svg);
                                    width: 15px;"
                             />
                         </div>
                        <div>${self.orderAttachBtn(self, order)}</div>
                        <style>
                            .attach{
                                padding: 2px; border: 1px solid #0091EA; color: #FFFFFF; background-color: #0091EA; transition: 0.3s
                            }
                            .attach-danger{
                                padding: 2px; border: 1px solid #8c1b00; color: #FFFFFF; background-color: #ea6d00; transition: 0.3s
                            }
                            .attach:hover{
                                color:  #0091EA; background-color: #FFFFFF;
                            }
                            .cancelAttach{
                                padding: 2px; border: 1px solid #C62828; color: #FFFFFF; background-color: #C62828; transition: 0.3s
                            }
                            .cancelAttach:hover{
                                color:  #C62828; background-color: #FFFFFF;
                            }
                        </style>
                        </div>`;
        },

        orderPlaceMark(order) {
            let color = null;
            if (order.common && order.common.emergency) {
                color = "#D32F2F";
            } else if (order.common) {
                color = "#E65100";
            } else {
                color = "#faa51c";
            }

            let path = order.preorder ? "/storage/img/user-preorder.svg" : "/storage/img/user.svg";

            return (
                `<div style="height: 25px; width: 25px; background-color: transparent; border-radius: 50%; border: 2px solid ` +
                color +
                `; display: flex; justify-content: center; align-items: center; box-shadow: 0 8px 11px -5px rgba(0,0,0,.2),0 17px 26px 2px rgba(0,0,0,.14),0 6px 32px 5px rgba(0,0,0,.12)!important;">
                           <img alt="client" style="height: 20px; width: 20px;" src="${path}">
                        </div>`
            );
        },

        createOrderPlaceMark(order) {
            let self = this;
            let balloonContentLayout = ymaps.templateLayoutFactory.createClass(self.orderBalloon(self, order), {
                build: function () {
                    balloonContentLayout.superclass.build.call(this);
                    self.orderPlaceMarkCallAction(self, order);

                    if (order.common) {
                        if (!order.current_shipped || 1 === !order.current_shipped.status.status) {
                            self.placeMarkOptions.order.attached
                                ? self.orderPlaceMarkCancelAttachAction(self, order)
                                : self.orderPlaceMarkAttachAction(self, order);
                        }
                    } else {
                        self.orderToManualy(self, order);
                    }
                },
                clear: function () {
                    balloonContentLayout.superclass.clear.call(this);
                },
            });

            let layout = ymaps.templateLayoutFactory.createClass(self.orderPlaceMark(order));
            return new ymaps.Placemark(
                [order.from_coordinates.lat, order.from_coordinates.lut],
                {
                    hintContent: order.status.text,
                },
                {
                    balloonContentLayout: balloonContentLayout,
                    iconLayout: layout,
                    iconShape: {
                        type: "Circle",
                        coordinates: [12, 12],
                        radius: 25,
                    },
                },
            );
        },

        addCancelOrderAttachBtn(self) {
            let ButtonLayout = ymaps.templateLayoutFactory.createClass(
                `<button id="attach-cancel-btn">Отменить прикрепление</button>
                            <style>
                               #attach-cancel-btn{
                                    padding: 4px;
                                    border: 1px solid #C62828;
                                    color: #FFFFFF;
                                    background-color: #C62828;
                                    transition: 0.3s;
                                    box-shadow: 0 2px 2px 1px rgba(0,0,0,.15), 0 2px 5px -3px rgba(0,0,0,.15);
                                    border-radius: 3px;
                                }
                               #attach-cancel-btn:hover{
                                    color:  #C62828; background-color: #FFFFFF;
                               }
                            </style>`,
                {
                    build: function () {
                        ButtonLayout.superclass.build.call(this);
                        document.getElementById("attach-cancel-btn").addEventListener(
                            "click",
                            function () {
                                self.mapSetObjects();
                            },
                            false,
                        );
                    },
                    clear: function () {
                        ButtonLayout.superclass.clear.call(this);
                    },
                },
            );
            self.cancelAttachBtn = new ymaps.control.Button({
                data: {},
                options: {
                    layout: ButtonLayout,
                    maxWidth: [170, 190, 220],
                },
            });
            self.map.controls.add(self.cancelAttachBtn, { float: "right" });
        },

        orderAttachBtn(self, order) {
            if (order.common) {
                return order.current_shipped && 1 === order.current_shipped.status.status
                    ? `<div style="display: flex; align-items: center">
                           <div style="margin-right: 10px">${self.loading()}</div>
                           <span>Заказ на рассмотрении...</span>
                       </div>`
                    : `<button
                           id="order-attach-${order.order_id}"
                           class="${self.placeMarkOptions.order.attached ? "cancelAttach" : "attach"}"
                       >
                           ${self.placeMarkOptions.order.attached ? "Отменить прикрепление" : "Прикрепить к водителю"}
                       </button>

                    <select id="order-attach-${order.order_id}"
                        class="ml-2 black--text custom-select
                        pointer${self.placeMarkOptions.order.foreignBoard ? "cancelAttach" : "attach"}"
                    >
                        <option value="" selected disabled hidden>Внешний борт</option>
                        <option value="1">Yandex taxi</option>
                        <option value="2">Gett taxi</option>
                    </select>`;
            } else {
                return (
                    `<div style="display: flex; align-items: center">
                    <div style="margin-right: 10px">` +
                    self.loading() +
                    `</div>
                    <span>Заказ на распределении...</span>
                    </div>` +
                    `<button id="order-manualy-${order.order_id}" style="display: flex; align-items: center" class="float-right attach">
                        Ручное
                    </button>`
                );
            }
        },

        removePlaceMarksByType(type) {
            this.mapObjects.forEach(item => {
                if (item.type === type) {
                    this.geoObjects.remove(item.placeMark);
                }
            });
            this.mapObjects = this.mapObjects.filter(item => {
                return item.type !== type;
            });
        },
    },
};
