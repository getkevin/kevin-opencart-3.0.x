<?php
/*
* 2020 kevin. payment  for OpenCart version 3.0.x.x
* @version 1.0.1.5
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
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Payment (verze '.KEVIN_VERSION.')';

// Text
$_['text_clear_success'] = 'Úspěch: Úspěšně jste vymazali protokol!';
$_['text_edit'] = 'Upravit modul kevin. payment';
$_['text_extension'] = 'Rozšíření';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_left'] = 'Vlevo';
$_['text_payment'] = 'Platba';
$_['text_payment_log'] = 'Protokol platby';
$_['text_refund_log'] = 'Protokol refundace';
$_['text_right'] = 'Vpravo';
$_['text_select_action'] = '-Vybrat Akce-';
$_['text_select_status'] = '-Vybrat stav-';
$_['text_success'] = 'Úspěch: Změnili jste podrobnosti kevin.module!';

// Entry
$_['entry_bank_name_enabled'] = 'Název banky';
$_['entry_client_company'] = 'Název společnosti klienta:';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_iban'] = 'Číslo účtu klienta:';
$_['entry_client_id'] = 'Client ID:';
$_['entry_client_secret'] = 'Client Secret:';
$_['entry_completed_status'] = 'Dokončit';
$_['entry_created_refund_action'] = 'Refundace je připravena';
$_['entry_failed_status'] = 'Selhalo';
$_['entry_full_refund_action'] = 'Plně refundováno';
$_['entry_general'] = 'Obecné';
$_['entry_geo_zone'] = 'Geografická zóna:';
$_['entry_image'] = 'Platba kevin. Obrázek loga';
$_['entry_image_height'] = 'Maximální výška obrázku v px';
$_['entry_image_width'] = 'Maximální šířka obrázku v px';
$_['entry_instructions'] = 'Platební pokyny';
$_['entry_instruction_title'] = 'Název pokynu';
$_['entry_kevin_instruction'] = 'Pokyny k potvrzovacímu kroku. HTML není podporováno';
$_['entry_kevin_title'] = 'Název platební metody';
$_['entry_log'] = 'kevin. log:';
$_['entry_order_status'] = 'Stav objednávky';
$_['entry_order_statuses'] = 'Stavy objednávky';
$_['entry_partial_refund_action'] = 'Částečně refundováno';
$_['entry_partial_refund_status'] = 'Stav částečně refundované objednávky';
$_['entry_payment_log'] = 'Protokol platby';
$_['entry_pending_status'] = 'Čeká na vyřízení';
$_['entry_position'] = 'Pozice loga platby';
$_['entry_redirect_preferred'] = 'Upřednostňuje se přesměrování';
$_['entry_refunded_status'] = 'Stav refundované objednávky';
$_['entry_refund_actions'] = 'Akce spojené s refundací';
$_['entry_refund_log'] = 'Protokol refundace';
$_['entry_refund_status'] = 'Stav refundace';
$_['entry_sort_order'] = 'Třídit objednávku:';
$_['entry_started_status'] = 'Zahájeno';
$_['entry_status'] = 'Status:';
$_['entry_total'] = 'Celkem';

// Error
$_['error_bcmod'] = 'Není možné ověřit účet č., protože na vašem serveru není nainstalován PHP modul &quot;bcmath&quot;! Nainstalujte si prosím modul &quot;bcmath&quot; nebo požádejte poskytovatele serveru o instalaci modulu „bcmath“.';
$_['error_client_company'] = 'Vyžaduje se název společnosti klienta!';
$_['error_client_c_symbol'] = 'Speciální znaky ve jménu klienta nejsou přijatelné!';
$_['error_client_endpointSecret'] = 'Vyžaduje se podpis klienta!';
$_['error_client_iban_empty'] = 'Vyžaduje se číslo účtu klienta!';
$_['error_client_iban_valid'] = 'Číslo účtu klienta není platné!';
$_['error_client_id'] = 'Vyžaduje se ID klienta!';
$_['error_client_secret'] = 'Vyžaduje se tajný kód klienta!';
$_['error_completed_status'] = 'Vyžaduje se stav objednávky!';
$_['error_created_action'] = 'Refundace vyžaduje akci!';
$_['error_failed_status'] = 'Vyžaduje se stav objednávky!';
$_['error_partial_action'] = 'Refundace vyžaduje akci!';
$_['error_partial_status'] = 'Vyžaduje se stav objednávky!';
$_['error_payment_log_warning'] = 'Varování: Soubor protokolu platby %s je %s!';
$_['error_pending_status'] = 'Vyžaduje se stav objednávky!';
$_['error_permission'] = 'Varování: K úpravě platby kevin nemáte oprávnění.';
$_['error_refunded_action'] = 'Refundace vyžaduje akci!';
$_['error_refunded_status'] = 'Vyžaduje se stav objednávky!';
$_['error_refund_log_warning'] = 'Varování: Soubor protokolu refundace %s je %s!';
$_['error_started_status'] = 'Vyžaduje se stav objednávky!';
$_['error_title'] = 'Vyžaduje se název platby nebo logo platby!';
$_['error_warning'] = 'Pečlivě zkontrolujte, zda v nastavení nejsou chyby!';

// Help
$_['help_bank_name_enbl'] = 'Povolit název banky na stránce pokladny.';
$_['help_bank_title'] = 'Místo názvu platební metody můžete přidat pouze logo banky.';
$_['help_client_endpointSecret'] = 'Váš tajný kód koncového bodu z řídícího panelu kevin. dashboard.';
$_['help_client_id'] = 'Vaše ID klienta z řídícího panelu kevin. dashboard.';
$_['help_client_secret'] = 'Váš tajný kód klienta z řídícího panelu kevin. dashboard.';
$_['help_height'] = 'Nastavte maximální výšku obrázku loga platby v px pro platební metodu pro krok platby v pokladně. Šířka obrázku se změní proporcionálně.';
$_['help_iban_format'] = 'Formát čísla účtu pro Litvu by měl obsahovat dvě písmena a 18 číslic. Příklad: LT599386327515536498.';
$_['help_log'] = 'Pokud je povolena funkce kevin. log, uloží se soubory kevin_payment.log a kevin_refund.log. Můžete je snadno zkontrolovat, stáhnout nebo vymazat.';
$_['help_position'] = 'Umístění loga platební metody vedle názvu platební metody.';
$_['help_total'] = 'Minimální částka objednávky';
$_['help_width'] = 'Nastavte maximální šířku obrázku loga platby v px pro platební metodu pro krok platby v pokladně. Výška obrázku se změní proporcionálně.';
