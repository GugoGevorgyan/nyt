<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->production();
            return;
            $this->call(OrderShippedStatusTableSeeder::class);
    }

        $this->local();
    }

    protected function production(): void
    {
        $this->call(TimezonesTableSeeder::class);
        $this->call(AddressesTableSeeder::class);
        $this->call(RoutesTableSeeder::class);

        $this->call(CountriesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);

        $this->call(MechanicQuestionTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(MenuRoleTableSeeder::class);
        $this->call(SuperAdminTableSeeder::class);
        $this->call(LegalEntityTypesTableSeeder::class);
        $this->call(LegalEntityBanksTableSeeder::class);
        $this->call(LegalEntitiesTableSeeder::class);
        $this->call(FranchiseeTableSeeder::class);
        $this->call(FranchiseeModuleTableSeeder::class);
        $this->call(DriverRatingPatternsTableSeeder::class);
        $this->call(SystemWorkersTableSeeder::class);
        $this->call(WorkerRoleTableSeeder::class);
        $this->call(ParksTableSeeder::class);
        $this->call(FranchisePhonesTableSeeder::class);
        $this->call(FranchiseSubPhonesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(AdminCorporateSeeder::class);
        $this->call(CompanyPhonesTableSeeder::class);
        $this->call(DriverTypesTableSeeder::class);
        $this->call(DriverSubtypesTableSeeder::class);
        $this->call(DriverTypeOptionalsTableSeeder::class);
        $this->call(DriverTypeOptionalOptionTableSeeder::class);
        $this->call(DriverGraphicsTableSeeder::class);
        $this->call(CarsClassTableSeeder::class);
        $this->call(PaymentTypesTableSeeder::class);
        $this->call(DriverLicenseTypesSeeder::class);
        $this->call(DriversCurrentStatusSeeder::class);
        $this->call(LearnStatusesTableSeeder::class);
        $this->call(DriverRatingLevelsTableSeeder::class);
        $this->call(CarStatusesTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(CarOptionsTableSeeder::class);
        $this->call(OrderTypesTableSeeder::class);
        $this->call(WorkerOperatorsTableSeeder::class);
        $this->call(WorkerDispatchersTableSeeder::class);
        $this->call(OrderStatusesTableSeeder::class);
        $this->call(OrderShippedStatusTableSeeder::class);
        $this->call(EstimatedRatingsTableSeeder::class);
        $this->call(TerminalsTableSeeder::class);
        $this->call(ComplaintStatusTableSeeder::class);
        $this->call(ApiClientsTableSeeder::class);

        $this->call(OauthPersonalAccessClientsTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);

        $this->call(TariffPriceTypesTableSeeder::class);
        $this->call(TariffsTableSeeder::class);
        $this->call(TariffRegionsCitiesTableSeeder::class);
        $this->call(TariffDestinationsTableSeeder::class);
        $this->call(TariffRegionBehindTableSeeder::class);
        $this->call(TariffRentTableSeeder::class);
        $this->call(TariffRentsTableSeeder::class);
        $this->call(TariffRentAltTableSeeder::class);

        $this->call(CompanyTariffTableSeeder::class);
        $this->call(ClassOptionTariffTableSeeder::class);

        $this->call(OrderFeedbackOptionsTableSeeder::class);
        $this->call(OrderFeedbacksTableSeeder::class);
        $this->call(MetrosTableSeeder::class);
        $this->call(RailwayStationsTableSeeder::class);
        $this->call(AirportsTableSeeder::class);
        $this->call(DebtRepaymentTableSeeder::class);
        $this->call(FranchiseOptionsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(ApiKeysTableSeeder::class);

        $this->call(AdminCorporatesTableSeeder::class);
        $this->call(VersioningTableSeeder::class);

        $this->call(FranchiseRegionTableSeeder::class);
        $this->call(FranchiseCityTableSeeder::class);
        $this->call(FranchiseModuleTableSeeder::class);

        $this->call(DriverTypeOptionTableSeeder::class);

    }

    protected function local(): void
    {
        $this->call(TimezonesTableSeeder::class);
        $this->call(AddressesTableSeeder::class);
        $this->call(RoutesTableSeeder::class);
        $this->call(MechanicQuestionTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(RegionsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(SuperAdminTableSeeder::class);
        $this->call(LegalEntityTypesTableSeeder::class);
        $this->call(LegalEntityBanksTableSeeder::class);
        $this->call(LegalEntitiesTableSeeder::class);
        $this->call(FranchiseeTableSeeder::class);
        $this->call(FranchiseeModuleTableSeeder::class);
        $this->call(DriverRatingPatternsTableSeeder::class);
        $this->call(SystemWorkersTableSeeder::class);
        $this->call(WorkerRoleTableSeeder::class);
        $this->call(ParksTableSeeder::class);
        $this->call(FranchisePhonesTableSeeder::class);
        $this->call(FranchiseSubPhonesTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(AdminCorporateSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(CompanyPhonesTableSeeder::class);
        $this->call(DriverTypesTableSeeder::class);
        $this->call(DriverSubtypesTableSeeder::class);
        $this->call(DriverTypeOptionalsTableSeeder::class);
        $this->call(DriverTypeOptionalOptionTableSeeder::class);
        $this->call(DriverGraphicsTableSeeder::class);
        $this->call(CarsClassTableSeeder::class);
        $this->call(PaymentTypesTableSeeder::class);
//        $this->call(CarCrashesTableSeeder::class);
        $this->call(DriverLicenseTypesSeeder::class);
        $this->call(DriverInfoTableSeeder::class);
        $this->call(DriversCurrentStatusSeeder::class);
        $this->call(LearnStatusesTableSeeder::class);
        $this->call(DriverCandidateSeeder::class);
        $this->call(DriverRatingLevelsTableSeeder::class);
        $this->call(CarStatusesTableSeeder::class);
        $this->call(CarsTableSeeder::class);
        $this->call(DriversTableSeeder::class);
        $this->call(DriverContractsTableSeeder::class);
        $this->call(DriverSchedulesTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(CarOptionsTableSeeder::class);
        $this->call(OrderTypesTableSeeder::class);
        $this->call(WorkerOperatorsTableSeeder::class);
        $this->call(WorkerDispatchersTableSeeder::class);
        $this->call(OrderStatusesTableSeeder::class);
        $this->call(CorporateClientTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(OrderCorporatesTableSeeder::class);
        $this->call(OrderWorkerCommentsTableSeeder::class);
        $this->call(ClientCallsTableSeeder::class);
//        $this->call(SystemRatingDriversTableSeeder::class);
        $this->call(OrderShippedStatusTableSeeder::class);
        $this->call(EstimatedRatingsTableSeeder::class);
        $this->call(OrderingShipmentDriversTableSeeder::class);
        $this->call(CompletedOrdersTableSeeder::class);
        $this->call(OrderStagesCordTableSeeder::class);
        $this->call(OrderOnWayRoadsTableSeeder::class);
        $this->call(OrderInProcessRoadsTableSeeder::class);
        $this->call(OrderProcessesTableSeeder::class);
        $this->call(CanceledOrdersTableSeeder::class);
        $this->call(ClientAddressTableSeeder::class);
        $this->call(TerminalsTableSeeder::class);
        $this->call(WaybillTableSeeder::class);
        $this->call(ComplaintStatusTableSeeder::class);
        $this->call(ComplaintTableSeeder::class);
        $this->call(ComplaintFilesTableSeeder::class);
        $this->call(ComplaintCommentsTableSeeder::class);
        $this->call(ClientSettingsTableSeeder::class);
        $this->call(ApiClientsTableSeeder::class);
        $this->call(OauthPersonalAccessClientsTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);

        $this->call(TariffPriceTypesTableSeeder::class);
        $this->call(TariffsTableSeeder::class);
        $this->call(TariffRegionsCitiesTableSeeder::class);
        $this->call(TariffDestinationsTableSeeder::class);
        $this->call(TariffRegionBehindTableSeeder::class);

        $this->call(OrderFeedbackOptionsTableSeeder::class);
        $this->call(OrderFeedbacksTableSeeder::class);
        $this->call(MetrosTableSeeder::class);
        $this->call(RailwayStationsTableSeeder::class);
        $this->call(AirportsTableSeeder::class);
        $this->call(DebtRepaymentTableSeeder::class);
        $this->call(FranchiseOptionsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(ApiKeysTableSeeder::class);
        $this->call(CompanyTariffTableSeeder::class);
        $this->call(ClassOptionTariffTableSeeder::class);

        $this->call(TariffRentTableSeeder::class);
        $this->call(MenuRoleTableSeeder::class);
        $this->call(AdminCorporatesTableSeeder::class);
        $this->call(VersioningTableSeeder::class);
        $this->call(FranchiseRegionTableSeeder::class);
        $this->call(FranchiseCityTableSeeder::class);
        $this->call(FranchiseModuleTableSeeder::class);
        $this->call(DriverTypeOptionTableSeeder::class);
        $this->call(TariffRentsTableSeeder::class);
        $this->call(TariffRentAltTableSeeder::class);
    }
}
