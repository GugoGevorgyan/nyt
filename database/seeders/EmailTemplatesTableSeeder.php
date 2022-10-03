<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class EmailTemplatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->delete();

        DB::table('email_templates')->insert([
            0 =>
                [
                    'body' => '<p>Your key [key]</p>
<p>Thank you very much for joining Our Company [company_name].</p>
<p>To Key copy and paste viewed input</p>
<p><br />Thanks again,<br /></p>',
                    'created_at' => null,
                    'description' => null,
                    'email_template_id' => 1,
                    'subject' => 'Confirm ClientMessage Register Code',
                    'type' => 1,
                    'updated_at' => null,
                ],
            1 =>
                [
                    'body' => 'Hello. Now you are already an employee of the system, congratulations. Your profile information is the following login <span style=style="font-weight:bold">[login]</span> password <span style=style="font-weight:bold">[password]</span> you can
go to the following link to log in and after that you can change your profile data. thank',
                    'created_at' => null,
                    'description' => ' congratulations. Your profile information is the following username password',
                    'email_template_id' => 2,
                    'subject' => 'Send System WorkerWeb created account data',
                    'type' => 2,
                    'updated_at' => null,
                ],
            2 =>
                [
                    'body' => '<h3>Hello. [days] days left until insurance expired for car:</h3>
                                <table>
                                <tr>
                                    <td>Mark</td>
                                    <td>[mark]</td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td>[model]</td>
                                </tr>
                                <tr>
                                    <td>Vin Code</td>
                                    <td>[vin_code]</td>
                                </tr>
                                <tr>
                                    <td>Drivers</td>
                                    <td>[drivers]</td>
                                </tr>
                                </table>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 3,
                    'subject' => 'days left until insurance expired',
                    'type' => 3,
                    'updated_at' => null,
                ],
            3 =>
                [
                    'body' => '<h3>Hello. Insurance expired for car:</h3>
                                <table>
                                <tr>
                                    <td>Mark</td>
                                    <td>[mark]</td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td>[model]</td>
                                </tr>
                                <tr>
                                    <td>Vin Code</td>
                                    <td>[vin_code]</td>
                                </tr>
                                <tr>
                                    <td>Drivers</td>
                                    <td>[drivers]</td>
                                </tr>
                                </table>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 4,
                    'subject' => 'Insurance expired',
                    'type' => 4,
                    'updated_at' => null,
                ],

            4 =>
                [
                    'body' => '<h3>Hello. [days] days left until inspection expired for car:</h3>
                                <table>
                                <tr>
                                    <td>Mark</td>
                                    <td>[mark]</td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td>[model]</td>
                                </tr>
                                <tr>
                                    <td>Vin Code</td>
                                    <td>[vin_code]</td>
                                </tr>
                                <tr>
                                    <td>Drivers</td>
                                    <td>[drivers]</td>
                                </tr>
                                </table>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 5,
                    'subject' => 'days left until inspection expired',
                    'type' => 5,
                    'updated_at' => null,
                ],
            5 =>
                [
                    'body' => '<h3>Hello. Inspection expired for car:</h3>
                                <table>
                                <tr>
                                    <td>Mark</td>
                                    <td>[mark]</td>
                                </tr>
                                <tr>
                                    <td>Model</td>
                                    <td>[model]</td>
                                </tr>
                                <tr>
                                    <td>Vin Code</td>
                                    <td>[vin_code]</td>
                                </tr>
                                <tr>
                                    <td>Drivers</td>
                                    <td>[drivers]</td>
                                </tr>
                                </table>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 6,
                    'subject' => 'Inspection expired',
                    'type' => 6,
                    'updated_at' => null,
                ],
            6 =>
                [
                    'body' => '<h3>Hello. [days] days left until inspection expired for your car.</h3>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 7,
                    'subject' => 'Little time left until inspection expired',
                    'type' => 7,
                    'updated_at' => null,
                ],

            7 =>
                [
                    'body' => '<h3>Hello. [days] days left until insurance expired for your car.</h3>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 8,
                    'subject' => 'Little time left until insurance expired',
                    'type' => 8,
                    'updated_at' => null,
                ],

            8 =>
                [
                    'body' => '<h3>Hello. Your car inspection expired</h3>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 9,
                    'subject' => 'Inspection expired',
                    'type' => 9,
                    'updated_at' => null,
                ],

            9 =>
                [
                    'body' => '<h3>Hello. Your car insurance expired.</h3>',
                    'created_at' => null,
                    'description' => 'Traffic safety message',
                    'email_template_id' => 10,
                    'subject' => 'Insurance expired',
                    'type' => 10,
                    'updated_at' => null,
                ],
            10 =>
                [
                    'body' => 'Hello. The mechanic <span style=style="font-weight:bold">[name]</span> change his information.',
                    'created_at' => null,
                    'description' => null,
                    'email_template_id' => 11,
                    'subject' => 'Notify Admin About Mechanic Information Change.',
                    'type' => 11,
                    'updated_at' => null,
                ],
        ]);
    }
}
