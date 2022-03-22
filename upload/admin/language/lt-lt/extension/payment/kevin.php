<?php
/*
* 2020 kevin. payment  for OpenCart version 3.0.x.x
* @version 1.0.1.4
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
*  @author 2020 kevin. <help@kevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
// Heading
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Mokėjimai (versija 1.0.1.4)';

// Text
$_['text_payment'] = 'Mokėjimai';
$_['text_edit'] = 'Redaguoti kevin. mokėjimo modulį';
$_['text_extension'] = 'Moduliai';
$_['text_success'] = 'Sėkmė: Jūs modifikavote kevin. modulį!';
$_['text_clear_success'] = 'Sėkmė: sėkmingai išvalėte veiksmų žurnalą!';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_right'] = 'Dešinėje';
$_['text_left'] = 'Kairėje';
$_['text_select_status'] = '-Pasirinkite būseną-';
$_['text_select_action'] = '-Pasirinkite veiksmą-';
$_['text_payment_log'] = 'Mokėjimų veiksmų žurnalas';
$_['text_refund_log'] = 'Grąžinimų veiksmų žurnalas';

// Entry
$_['entry_general'] = 'Pagrindiniai';
$_['entry_order_statuses'] = 'Užsakymų statusai';
$_['entry_refund_actions'] = 'Grąžinimų veiksmai';
$_['entry_instructions'] = 'Mokėjimo instrukcijos';
$_['entry_client_id'] = 'Kliento Id: ';
$_['entry_client_secret'] = 'Kliento slaptas kodas: ';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_company'] = 'Kliento įmonės pavadinimas: ';
$_['entry_client_iban'] = 'Kliento Sąskaitos Nr.: ';
$_['entry_redirect_preferred'] = 'Peradresuoti pirmenybė';
$_['entry_image'] = 'Kevin Logotipas';
$_['entry_image_height'] = 'Maksimalus logotipo aukštis px';
$_['entry_image_width'] = 'Maksimalus logotipo plotis px';
$_['entry_position'] = 'Logotipo vieta';
$_['entry_bank_name_enabled'] = 'Bank pavadinimas';
$_['entry_kevin_title'] = 'Mokėjimo būdo pavadinimas';
$_['entry_instruction_title'] = 'Instrukcijos pavadinimas';
$_['entry_kevin_instruction'] = 'Instrukcija užsakymo patvirtinimo žingsnyje. Nepalaiko HTML.';
$_['entry_total'] = 'Viso: ';
$_['entry_order_status'] = 'Užsakymo statusas:';
$_['entry_started_status'] = 'Pradėtas';
$_['entry_completed_status'] = 'Baigtas';
$_['entry_pending_status'] = 'Laukiama';
$_['entry_failed_status'] = 'Nepavykęs';
$_['entry_partial_refund_status'] = 'Dalinai grąžinto užsakymo statusas';
$_['entry_refunded_status'] = 'Grąžinto užsakymo statusas';
$_['entry_created_refund_action'] = 'Grąžinimas paruoštas';
$_['entry_partial_refund_action'] = 'Dalinai grąžinta';
$_['entry_full_refund_action'] = 'Pilnai grąžinta';
$_['entry_geo_zone'] = 'Geo Zona:';
$_['entry_status'] = 'Statusas:';
$_['entry_log'] = 'Kevin log:';
$_['entry_sort_order'] = 'Eilės tvarka:';
$_['entry_refund_status'] = 'Grąžinimų statusas';
$_['entry_payment_log'] = 'Mokėjimų veiksmų žurnalas';
$_['entry_refund_log'] = 'Grąžinimų veiksmų žurnalas';

// Error
$_['error_warning'] = 'Atidžiai patikrinkite nustatymus, ar nėra klaidų!';
$_['error_permission'] = 'Įspėjimas: Jūs neturite leidimo redaguoti mokėjimo modulio Kevin!';
$_['error_client_id'] = 'Kliento Id privalomas!';
$_['error_client_secret'] = 'Kliento slaptas kodas privalomas!';
$_['error_client_endpointSecret'] = 'Kliento slaptas parašas privalomas!';
$_['error_client_company'] = 'Kliento įmonės pavadinimas privalomas!';
$_['error_client_iban_empty'] = 'Kliento sąskaitos Nr. privalomas!';
$_['error_client_iban_valid'] = 'Kliento sąskaitos Nr. Negalioja!';
$_['error_title'] = 'Mokėjimo būdo pavadinimas, arba banko logotipas privalomas!';
$_['error_bcmod'] = 'Neįmanoma patvirtinti sąskaitos Nr., kadangi PHP Modulis "bcmath" nėra įdiegtas Jūsų serveryje! Idiekite "bcmath" modulį, arba paprašykite tai padaryti serverio administratoriaus.';
$_['error_started_status'] = 'Užsakymo būsena privaloma!';
$_['error_pending_status'] = 'Užsakymo būsena privaloma!';
$_['error_failed_status'] = 'Užsakymo būsena privaloma!';
$_['error_completed_status'] = 'Užsakymo būsena privaloma!';
$_['error_partial_status'] = 'Užsakymo būsena privaloma!';
$_['error_refunded_status'] = 'Užsakymo būsena privaloma!';
$_['error_created_action'] = 'Grąžinimo veiksmo būsena privaloma!';
$_['error_refunded_action'] = 'Grąžinimo veiksmo būsena privaloma!';
$_['error_partial_action'] = 'Grąžinimo veiksmo būsena privaloma!';
$_['error_refund_log_warning'] = 'Įspėjimas: Grąžinimo veiksmų žurnalo byla %s yra %s!';
$_['error_payment_log_warning'] = 'Įspėjimas: Mokėjimų veiksmų žurnalo byla %s yra %s!';

// Help
$_['help_iban_format'] = 'Sąskaitos Nr. formatas Lietuvai turi būti dvi raidės ir 18 skaičių. Pvz: LT599386327515536498.';
$_['help_bank_name_enbl'] = 'Rodyti galimų bankų pavadinimus atsiskaitymo puslapyje.';
$_['help_client_id'] = 'Jūsų kliento ID (Client ID). Jūs galite jį gauti Kevin. platformos konsolėje.';
$_['help_client_secret'] = 'Jūsų slaptas kodas (Client Secret). Jūs galite jį gauti Kevin. platformos konsolėje.';
$_['help_client_endpointSecret'] = 'Jūsų EndpointSecret. Jūs galite jį gauti Kevin. platformos konsolėje.';
$_['help_bank_title'] = 'Vietoje mokėjimo būdo pavadinimo galite pridėti tik banko logotipą.';
$_['help_total'] = 'Atsiskaitymo suma, kada atsiskaitymo galimybė bus aktyvuota.';
$_['help_log'] = 'Jeigu &quot;Kevin log&quot; įjungtas, kevin_payment.log ir kevin_refund.log bus įrašomi ir juos lengvai galėsite patirkinti, atsisiųsti, arba išvalyti.';
$_['help_width'] = 'Nustatykite maksimalų logotipo plotį px  &quot;Mokėjimo metodui&quot; atsiskaitymo mokėjimo pasirinkimo žingsnyje. Logotipo aukštis bus pakeistas proporcingai.';
$_['help_height'] = 'Nustatykite maksimalų logotipo aukštį px  &quot;Mokėjimo metodui&quot; atsiskaitymo mokėjimo pasirinkimo žingsnyje. Logotipo plotis bus pakeistas proporcingai.';
$_['help_position'] = 'Mokėjimo būdo logotipo vieta šalia mokėjimo metodo pavadinimo.';
