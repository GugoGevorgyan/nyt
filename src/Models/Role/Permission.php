<?php

declare(strict_types=1);

namespace Src\Models\Role;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use ReflectionException;
use ServiceEntity\Models\ServiceModel;
use Src\Core\Additional\Guard;
use Src\Core\Additional\RoleRegister;
use Src\Core\Contracts\PermissionModelContract;
use Src\Core\Contracts\PermissionModelContract as PermissionContract;
use Src\Core\Traits\HasRoles;
use Src\Core\Traits\RefreshRoleCache;
use Src\Exceptions\Role\PermissionAlreadyExists;
use Src\Exceptions\Role\PermissionDoesNotExist;
use Src\Models\SystemUsers\SystemWorker;
use Src\Models\Views\Route;

/**
 * Class Permission
 *
 * @property int $permission_id
 * @property int $role_id
 * @property int $homepage_route_id
 * @property string $name
 * @property string $guard_name
 * @property string|null $alias
 * @property string|null $description
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Route $route
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static Builder|Permission onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereHomepageRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @method static Builder|Permission withTrashed()
 * @method static Builder|Permission withoutTrashed()
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel except($values = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel distanceCord($latitude, $longitude, $distance = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel geofence($latitude, $longitude, $inner_radius, $outer_radius)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel disatnceCordsss($latitude, $longitude, $distance)
 * @property int|null $route_id
 * @property-read \Illuminate\Database\Eloquent\Collection|Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|SystemWorker[] $users
 * @property-read int|null $users_count
 * @property string $text
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceModel cordDistance($latitude, $longitude)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 */
class Permission extends ServiceModel implements PermissionModelContract
{
    use HasRoles;
    use RefreshRoleCache;
    use SoftDeletes;

    public const SHOW_PARK_WEB = 'show_park_web';
    /**
     *
     */
    public const SHOW_PARK_API = 'show_park_api';
    /**
     *
     */
    public const CREATE_PARK_WEB = 'create_park_web';
    /**
     *
     */
    public const CREATE_PARK_API = 'create_park_api';
    /**
     *
     */
    public const EDIT_PARK_WEB = 'edit_park_web';
    /**
     *
     */
    public const EDIT_PARK_API = 'edit_park_api';
    /**
     *
     */
    public const REMOVE_PARK_WEB = 'remove_park_web';
    /**
     *
     */
    public const REMOVE_PARK_API = 'remove_park_api';
    /**
     *
     */
    public const DELETE_PARK_WEB = 'delete_park_web';
    /**
     *
     */
    public const DELETE_PARK_API = 'delete_park_api';
    /**
     *
     */
    public const SHOW_DRIVERS_WEB = 'show_drivers_web';
    /**
     *
     */
    public const SHOW_DRIVERS_API = 'show_drivers_api';
    /**
     *
     */
    public const CREATE_DRIVERS_WEB = 'create_drivers_web';
    /**
     *
     */
    public const CREATE_DRIVERS_API = 'create_drivers_api';
    /**
     *
     */
    public const EDIT_DRIVERS_WEB = 'edit_drivers_web';
    /**
     *
     */
    public const EDIT_DRIVERS_API = 'edit_drivers_api';
    /**
     *
     */
    public const REMOVE_DRIVERS_WEB = 'remove_drivers_web';
    /**
     *
     */
    public const REMOVE_DRIVERS_API = 'remove_drivers_api';
    /**
     *
     */
    public const DELETE_DRIVERS_WEB = 'delete_drivers_web';
    /**
     *
     */
    public const DELETE_DRIVERS_API = 'delete_drivers_api';
    /**
     *
     */
    public const SHOW_CLIENTS_WEB = 'show_clients_web';
    /**
     *
     */
    public const SHOW_CLIENTS_API = 'show_clients_api';
    /**
     *
     */
    public const CREATE_CLIENTS_WEB = 'create_clients_web';
    /**
     *
     */
    public const CREATE_CLIENTS_API = 'create_clients_api';
    /**
     *
     */
    public const EDIT_CLIENTS_WEB = 'edit_clients_web';
    /**
     *
     */
    public const EDIT_CLIENTS_API = 'edit_clients_api';
    /**
     *
     */
    public const REMOVE_CLIENTS_WEB = 'remove_clients_web';
    /**
     *
     */
    public const REMOVE_CLIENTS_API = 'remove_clients_api';
    /**
     *
     */
    public const DELETE_CLIENTS_WEB = 'delete_clients_web';
    /**
     *
     */
    public const DELETE_CLIENTS_API = 'delete_clients_api';
    /**
     *
     */
    public const CREATE_DRIVER_CANDIDATE_WEB = 'create_driver_candidate_web';
    /**
     *
     */
    public const CREATE_DRIVER_CANDIDATE_API = 'create_driver_candidate_api';
    /**
     *
     */
    public const EVALUATE_DRIVER_CANDIDATE_WEB = 'evaluate_driver_candidate_web';
    /**
     *
     */
    public const EVALUATE_DRIVER_CANDIDATE_API = 'evaluate_driver_candidate_api';
    /**
     *
     */
    public const DELETE_DRIVER_CANDIDATE_WEB = 'delete_driver_candidate_web';
    /**
     *
     */
    public const DELETE_DRIVER_CANDIDATE_API = 'delete_driver_candidate_api';
    /**
     *
     */
    public const CAR_REGISTER_WEB = 'car_register_web';
    /**
     *
     */
    public const CAR_REGISTER_API = 'car_register_api';
    /**
     *
     */
    public const CAR_BUY_WEB = 'car_buy_web';
    /**
     *
     */
    public const CAR_BUY_API = 'car_buy_api';
    /**
     *
     */
    public const CAR_TRANSFER_WEB = 'car_transfer_web';
    /**
     *
     */
    public const CAR_TRANSFER_API = 'car_transfer_api';
    /**
     *
     */
    public const ATTACHING_CAR_WEB = 'attaching_car_web';
    /**
     *
     */
    public const ATTACHING_CAR_API = 'attaching_car_api';
    /**
     *
     */
    public const CAR_ONLAIN_CONTROL_WEB = 'car_onlain_control_web';
    /**
     *
     */
    public const CAR_ONLAIN_CONTROL_API = 'car_onlain_control_api';
    /**
     *
     */
    public const SHOW_CORPORATE_DRIVERS_WEB = 'show_corporate_drivers_web';
    /**
     *
     */
    public const SHOW_CORPORATE_DRIVERS_API = 'show_corporate_drivers_api';
    /**
     *
     */
    public const SHOW_AGREGATOR_DRIVERS_WEB = 'show_agregator_drivers_web';
    /**
     *
     */
    public const SHOW_AGREGATOR_DRIVERS_API = 'show_agregator_drivers_api';
    /**
     *
     */
    public const SHOW_CARS_DRIVERS_INFO_WEB = 'show_cars_drivers_info_web';
    /**
     *
     */
    public const SHOW_CARS_DRIVERS_INFO_API = 'show_cars_drivers_info_api';
    /**
     *
     */
    public const SHOW_DRIVERS_DEBTS_WEB = 'show_drivers_debts_web';
    /**
     *
     */
    public const SHOW_DRIVERS_DEBTS_API = 'show_drivers_debts_api';
    /**
     *
     */
    public const MECHANIC_WEB = 'mechanic_web';
    /**
     *
     */
    public const MECHANIC_API = 'mechanic_api';
    /**
     *
     */
    public const ORDER_BOOK_WEB = 'order_book_web';
    /**
     *
     */
    public const ORDER_BOOK_API = 'order_book_api';
    /**
     *
     */
    public const SHOW_OPERATOR_ORDERS_WEB = 'show_operator_orders_web';
    /**
     *
     */
    public const SHOW_OPERATOR_ORDERS_API = 'show_operator_orders_api';
    /**
     *
     */
    public const SHOW_ALL_ORDERS_WEB = 'show_all_orders_web';
    /**
     *
     */
    public const SHOW_ALL_ORDERS_API = 'show_all_orders_api';
    /**
     *
     */
    public const TRANSFER_ORDERS_WEB = 'transfer_orders_web';
    /**
     *
     */
    public const TRANSFER_ORDERS_API = 'transfer_orders_api';
    /**
     *
     */
    public const SHOW_ORDERS_MAP_WEB = 'show_orders_map_web';
    /**
     *
     */
    public const SHOW_ORDERS_MAP_API = 'show_orders_map_api';
    /**
     *
     */
    public const SHOW_DRIVERS_MAP_WEB = 'show_drivers_map_web';
    /**
     *
     */
    public const SHOW_DRIVERS_MAP_API = 'show_drivers_map_api';
    /**
     *
     */
    public const SHOW_DIRECTORY_PLACES_WEB = 'show_directory_places_web';
    /**
     *
     */
    public const SHOW_DIRECTORY_PLACES_API = 'show_directory_places_api';
    /**
     *
     */
    public const AUTOMATIC_ORDER_DISTRIBUTION_WEB = 'automatic_order_distribution_web';
    /**
     *
     */
    public const AUTOMATIC_ORDER_DISTRIBUTION_API = 'automatic_order_distribution_api';
    /**
     *
     */
    public const ORDER_SEARCH_WEB = 'order_search_web';
    /**
     *
     */
    public const SORDER_SEARCH_API = 'order_search_api';
    /**
     *
     */
    public const SMS_WHEN_CAR_ARRIVE_WEB = 'sms_when_car_arrive_web';
    /**
     *
     */
    public const SMS_WHEN_CAR_ARRIVE_API = 'sms_when_car_arrive_api';
    /**
     *
     */
    public const TARIFFS_WEB = 'tariffs_web';
    /**
     *
     */
    public const TARIFFS_API = 'tariffs_api';
    /**
     *
     */
    public const SERVICES_IN_ORDER_WEB = 'services_in_order_web';
    /**
     *
     */
    public const SERVICES_IN_ORDER_API = 'services_in_order_api';
    /**
     *
     */
    public const ADDITIONAL_FIELDS_WEB = 'additional_fields_web';
    /**
     *
     */
    public const ADDITIONAL_FIELDS_API = 'additional_fields_api';
    /**
     *
     */
    public const ORDER_INFO_WEB = 'order_info_web';
    /**
     *
     */
    public const ORDER_INFO_API = 'order_info_api';
    /**
     *
     */
    public const HIGHLIGHT_COLOR_WEB = 'highlight_color_web';
    /**
     *
     */
    public const HIGHLIGHT_COLOR_API = 'highlight_color_api';
    /**
     *
     */
    public const LOYALTY_PROGRAM_WEB = 'loyalty_program_web';
    /**
     *
     */
    public const LOYALTY_PROGRAM_API = 'loyalty_program_api';
    /**
     *
     */
    public const BIRN_ORDERS_WEB = 'birn_orders_web';
    /**
     *
     */
    public const BIRN_ORDERS_API = 'birn_orders_api';
    /**
     *
     */
    public const PRELIMINARY_ORDERS_WEB = 'preliminary_orders_web';
    /**
     *
     */
    public const PRELIMINARY_ORDERS_API = 'preliminary_orders_api';
    /**
     *
     */
    public const SHOW_CALL_PANEL_WEB = 'show_call_panel_web';
    /**
     *
     */
    public const SHOW_CALL_PANEL_API = 'show_call_panel_api';
    /**
     *
     */
    public const SHOW_BOARD_PANEL_WEB = 'show_board_panel_web';
    /**
     *
     */
    public const SHOW_BOARD_PANEL_API = 'show_board_panel_api';
    /**
     *
     */
    public const SHOW_CALL_STATISTICS_WEB = 'show_call_statistics_web';
    /**
     *
     */
    public const SHOW_CALL_STATISTICS_API = 'show_call_statistics_api';
    /**
     *
     */
    public const SHOW_CALL_COMMON_ORDER = 'view_common_orders_event';
    /**
     *
     */
    public const CALL_INTERCEPTION_WEB = 'call_interception_web';
    /**
     *
     */
    public const CALL_INTERCEPTION_API = 'call_interception_api';
    /**
     *
     */
    public const COMPLAINT_PROCESSING_WEB = 'complaint_processing_web';
    /**
     *
     */
    public const COMPLAINT_PROCESSING_API = 'complaint_processing_api';
    /**
     *
     */
    public const CREATE_TABS_WEB = 'create_tabs_web';
    /**
     *
     */
    public const CREATE_TABS_API = 'create_tabs_api';
    /**
     *
     */
    public const PHONE_NUMBER_SUBSTITUTION_WEB = 'phone_number_substitution_web';
    /**
     *
     */
    public const PHONE_NUMBER_SUBSTITUTION_API = 'phone_number_substitution_api';
    /**
     *
     */
    public const CORPORATE_CLIENTS_VERIFICATION_WEB = 'corporate_clients_verification_web';
    /**
     *
     */
    public const CORPORATE_CLIENTS_VERIFICATION_API = 'corporate_clients_verification_api';
    /**
     *
     */
    public const LOADING_ADRESS_TO_DIRECTORY_WEB = 'loading_adress_to_directory_web';
    /**
     *
     */
    public const LOADING_ADRESS_TO_DIRECTORY_API = 'loading_adress_to_directory_api';
    /**
     *
     */
    public const ORDER_LIST_FOR_SELECT_WEB = 'order_list_for_select_web';
    /**
     *
     */
    public const ORDER_LIST_FOR_SELECT_API = 'order_list_for_select_api';
    /**
     *
     */
    public const ORDERS_REPORT_WEB = 'orders_report_web';
    /**
     *
     */
    public const ORDERS_REPORT_API = 'orders_report_api';
    /**
     *
     */
    public const ORDERS_REPORT_BY_OPERATOR_WEB = 'orders_report_by_operator_web';
    /**
     *
     */
    public const ORDERS_REPORT_BY_OPERATOR_API = 'orders_report_by_operator_api';
    /**
     *
     */
    public const ORDERS_REPORT_BY_DRIVER_WEB = 'orders_report_by_driver_web';
    /**
     *
     */
    public const ORDERS_REPORT_BY_DRIVER_API = 'orders_report_by_driver_api';
    /**
     *
     */
    public const ORDERS_REPORT_BY_CLIENT_WEB = 'orders_report_by_client_web';
    /**
     *
     */
    public const ORDERS_REPORT_BY_CLIENT_API = 'orders_report_by_client_api';
    /**
     *
     */
    public const ORDERS_REPORT_BY_SOURCE_WEB = 'orders_report_by_source_web';
    /**
     *
     */
    public const ORDERS_REPORT_BY_SOURCE_API = 'orders_report_by_source_api';
    /**
     *
     */
    public const RENTAL_REPORT_WEB = 'rental_report_web';
    /**
     *
     */
    public const RENTAL_REPORT_API = 'rental_report_api';
    /**
     *
     */
    public const CASHLESS_SLIPS_BY_COMPANY_WEB = 'cashless_slips_by_company_web';
    /**
     *
     */
    public const CASHLESS_SLIPS_BY_COMPANY_API = 'cashless_slips_by_company_api';
    /**
     *
     */
    public const CASHLESS_TRIPS_WEB = 'cashless_trips_web';
    /**
     *
     */
    public const CASHLESS_TRIPS_API = 'cashless_trips_api';
    /**
     *
     */
    public const CASHLESS_UNDELIVERED_SLIPS_WEB = 'cashless_undelivered_slips_web';
    /**
     *
     */
    public const CASHLESS_UNDELIVERED_SLIPS_API = 'cashless_undelivered_slips_api';
    /**
     *
     */
    public const ACQUIRING_CHECKS_BY_COMPANY_WEB = 'acquiring_checks_by_company_web';
    /**
     *
     */
    public const ACQUIRING_CHECKS_BY_COMPANY_API = 'acquiring_checks_by_company_api';
    /**
     *
     */
    public const ACQUIRING_TRIPS_WEB = 'acquiring_trips_web';
    /**
     *
     */
    public const ACQUIRING_TRIPS_API = 'acquiring_trips_api';
    /**
     *
     */
    public const STATEMENT_FOT_ACCOUNTANT_WEB = 'statement_for_accountant_web';
    /**
     *
     */
    public const STATEMENT_FOT_ACCOUNTANT_API = 'statement_for_accountant_api';
    /**
     *
     */
    public const CASHLESS_COMPANY_ANALYTICS_WEB = 'cashless_company_analytics_web';
    /**
     *
     */
    public const CASHLESS_COMPANY_ANALYTICS_API = 'cashless_company_analytics_api';
    /**
     *
     */
    public const GASOLINE_REPORT_WEB = 'gasoline_report_web';
    /**
     *
     */
    public const GASOLINE_REPORT_API = 'gasoline_report_api';
    /**
     *
     */
    public const REPORT_COMPANY_CLIENT_WEB = 'report_company_client_web';
    /**
     *
     */
    public const REPORT_COMPANY_CLIENT_API = 'report_company_client_api';
    /**
     *
     */
    public const DISCOUNT_CALCULATION_WEB = 'discount_calculation_web';
    /**
     *
     */
    public const DISCOUNT_CALCULATION_API = 'discount_calculation_api';
    /**
     *
     */
    public const ADD_BILLING_TAG_WEB = 'add_billing_tag_web';
    /**
     *
     */
    public const ADD_BILLING_TAG_API = 'add_billing_tag_api';
    /**
     *
     */
    public const TRIPS_CATALOG_WITH_SELECTION_WEB = 'trips_catalog_with_selection_web';
    /**
     *
     */
    public const TRIPS_CATALOG_WITH_SELECTION_API = 'trips_catalog_with_selection_api';
    /**
     *
     */
    public const CASH_TRIP_CATALOG_FOR_ONE_PASSENGER_WEB = 'cash_trip_catalog_for_one_passenger_web';
    /**
     *
     */
    public const CASH_TRIP_CATALOG_FOR_ONE_PASSENGER_API = 'cash_trip_catalog_for_one_passenger_api';
    /**
     *
     */
    public const TENANTS_WORK_SCHEME_WEB = 'tenants_work_scheme_web';
    /**
     *
     */
    public const TENANTS_WORK_SCHEME_API = 'tenants_work_scheme_api';
    /**
     *
     */
    public const REPORT_ON_KK_WEB = 'report_on_kk_web';
    /**
     *
     */
    public const REPORT_ON_KK_API = 'report_on_kk_api';
    /**
     *
     */
    public const REPORT_ON_TENANTS_WEB = 'report_on_tenants_web';
    /**
     *
     */
    public const REPORT_ON_TENANTS_API = 'report_on_tenants_api';
    /**
     *
     */
    public const ORDERS_CATALOG_WEB = 'orders_catalog_web';
    /**
     *
     */
    public const ORDERS_CATALOG_API = 'orders_catalog_api';
    /**
     *
     */
    public const PARKING_REPORT_WEB = 'parking_report_web';
    /**
     *
     */
    public const PARKING_REPORT_API = 'parking_report_api';
    /**
     *
     */
    public const REPORT_FIRST_ORDER_WEB = 'report_first_order_web';
    /**
     *
     */
    public const REPORT_FIRST_ORDER_API = 'report_first_order_api';
    /**
     *
     */
    public const ORDER_DYNAMICS_WEB = 'order_dynamics_web';
    /**
     *
     */
    public const ORDER_DYNAMICS_API = 'order_dynamics_api';
    /**
     *
     */
    public const COUNTERPARTY_ORDERS_BY_MANAGER_WEB = 'counterparty_orders_by_manager_web';
    /**
     *
     */
    public const COUNTERPARTY_ORDERS_BY_MANAGER_API = 'counterparty_orders_by_manager_api';
    /**
     *
     */
    public const BEST_WORST_CLIENTS_WEB = 'best_worst_clients_web';
    /**
     *
     */
    public const BEST_WORST_CLIENTS_API = 'best_worst_clients_api';
    /**
     *
     */
    public const TRANSPORT_SERVICE_REPORT_WEB = 'transport_service_report_web';
    /**
     *
     */
    public const TRANSPORT_SERVICE_REPORT_API = 'transport_service_report_api';
    /**
     *
     */
    public const PERIOD_FIRST_ORDER_WEB = 'period_first_order_web';
    /**
     *
     */
    public const PERIOD_FIRST_ORDER_API = 'period_first_order_api';
    /**
     *
     */
    public const COUNTERPARTY_ON_MANAGER_WEB = 'counterparty_on_manager_web';
    /**
     *
     */
    public const COUNTERPARTY_ON_MANAGER_API = 'counterparty_on_manager_api';
    /**
     *
     */
    public const INACTIVE_CLIENTS_WEB = 'inactive_clients_web';
    /**
     *
     */
    public const INACTIVE_CLIENTS_API = 'inactive_clients_api';
    /**
     *
     */
    public const PERFORMANCE_PROF_ORDER_WEB = 'performance_prof_order_web';
    /**
     *
     */
    public const PERFORMANCE_PROF_ORDER_API = 'performance_prof_order_api';
    /**
     *
     */
    public const ORDERS_CATALOG_CBR_WEB = 'orders_catalog_cbr_web';
    /**
     *
     */
    public const ORDERS_CATALOG_CBR_API = 'orders_catalog_cbr_api';
    /**
     *
     */
    public const TRIP_DETAILS_WEB = 'trip_details_web';
    /**
     *
     */
    public const TRIP_DETAILS_API = 'trip_details_api';
    /**
     *
     */
    public const PROVIDING_CAR_TO_DRIVER_WEB = 'providing_car_to_driver_web';
    /**
     *
     */
    public const PROVIDING_CAR_TO_DRIVER_API = 'providing_car_to_driver_api';
    /**
     *
     */
    public const TAKING_CAR_FROM_DRIVER_WEB = 'taking_car_from_driver_web';
    /**
     *
     */
    public const TAKING_CAR_FROM_DRIVER_API = 'taking_car_from_driver_api';
    /**
     *
     */
    public const CASHBOX_WEB = 'cashbox_web';
    /**
     *
     */
    public const CASHBOX_API = 'cashbox_api';

    /**
     * @var string
     */
    protected $table = 'permissions';
    /**
     * @var string
     */
    protected $primaryKey = 'permission_id';
    /**
     * @var array
     */
    protected $fillable = ['name', 'alias', 'guard_name', 'description', 'role_id', 'text'];
    /**
     * @var array
     */
    protected $guarded = ['permission_id'];

    /**
     * Permission constructor.
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->setTable(config('permission.table_names.permissions'));
    }

    /**
     * Find a permission by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param  string|null  $guard_name
     *
     * @return PermissionContract
     * @throws ReflectionException
     */
    public static function findByName(string $name, $guard_name = null): PermissionContract
    {
        $guard_name = $guard_name ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['name' => $name, 'guard_name' => $guard_name])->first();
        if (!$permission) {
            throw PermissionDoesNotExist::create($name, $guard_name);
        }

        return $permission;
    }

    /**
     * Get the current cached permissions.
     * @param  array  $params
     * @return Collection
     */
    protected static function getPermissions(array $params = []): Collection
    {
        return app(RoleRegister::class)->getPermissions($params);
    }

    /**
     * Find a permission by its id (and optionally guardName).
     *
     * @param  int  $id
     * @param  string|null  $guard_name
     *
     * @return PermissionContract
     * @throws ReflectionException
     */
    public static function findById(int $id, $guard_name = null): PermissionContract
    {
        $guard_name = $guard_name ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['permission_id' => $id, 'guard_name' => $guard_name])->first();

        if (!$permission) {
            throw PermissionDoesNotExist::withId($id, $guard_name);
        }

        return $permission;
    }

    /**
     * Find or CreateComponents permission by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param  string|null  $guard_name
     *
     * @return PermissionContract
     * @throws ReflectionException
     */
    public static function findOrCreate(string $name, $guard_name = null): PermissionContract
    {
        $guard_name = $guard_name ?? Guard::getDefaultName(static::class);
        $permission = static::getPermissions(['name' => $name, 'guard_name' => $guard_name])->first();

        if (!$permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guard_name]);
        }

        return $permission;
    }

    /**
     * @param  array  $attributes
     * @return Model|ServiceModel
     * @throws ReflectionException
     */
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermissions(
            [
                'name' => $attributes['name'],
                'guard_name' => $attributes['guard_name']
            ]
        )->first();

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        if (is_not_lumen() && app()::VERSION < '5.4') {
            return parent::create($attributes);
        }

        return static::query()->create($attributes);
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * A permission belongs to some clients of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(SystemWorker::class, 'worker_permission', 'permission_id', 'system_worker_id');
    }

    /**
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, $this->primaryKey, 'route_id');
    }
}
