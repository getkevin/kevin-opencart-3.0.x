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
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Payment (wersja '.KEVIN_VERSION.')';

// Text
$_['text_clear_success'] = 'Sukces: Udało Ci się wyczyścić dziennik!';
$_['text_edit'] = 'Edytuj moduł płatności kevin.';
$_['text_extension'] = 'Rozszerzenia';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_left'] = 'Lewy';
$_['text_payment'] = 'Płatność';
$_['text_payment_log'] = 'Dziennik płatności';
$_['text_refund_log'] = 'Dziennik zwrotów';
$_['text_right'] = 'Prawo';
$_['text_select_action'] = '-Wybierz Działanie-';
$_['text_select_status'] = '-Wybierz status-';
$_['text_success'] = 'Sukces: Udało Ci się zmodyfikować szczegóły modułu kevin.!';
$_['text_refund'] = 'Zwroty są dozwolone.';
$_['text_payment_bank'] = 'Metoda płatności bankowej jest dozwolona.';
$_['text_payment_card'] = 'Płatność kartą jest dozwolona.';
$_['text_sandbox_alert'] = '<span style = "font-weight: 600; color: red;"> kevin. </span> Płatności są konfigurowane  w trybie Sandbox. Tylko dla płatności testowych. Brak dostępnych rzeczywistych płatności!';

// Entry
$_['entry_bank_name_enabled'] = 'Nazwa banku';
$_['entry_client_company'] = 'Nazwa firmy klienta:';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_iban'] = 'Numer konta klienta:';
$_['entry_client_id'] = 'Client ID:';
$_['entry_client_secret'] = 'Client Secret:';
$_['entry_completed_status'] = 'Ukończono';
$_['entry_created_refund_action'] = 'Gotowość do zwrotu pieniędzy';
$_['entry_failed_status'] = 'Nie powiodło się';
$_['entry_full_refund_action'] = 'Pełny zwrot kosztów';
$_['entry_general'] = 'Ogólne';
$_['entry_geo_zone'] = 'Strefa geograficzna:';
$_['entry_image'] = 'Płatność kevin. Obraz logo';
$_['entry_image_height'] = 'Maksymalna wysokość obrazu w pikselach';
$_['entry_image_width'] = 'Maksymalna szerokość obrazu w pikselach';
$_['entry_instructions'] = 'Instrukcje dotyczące płatności';
$_['entry_instruction_title'] = 'Tytuł instrukcji';
$_['entry_kevin_instruction'] = 'Instrukcja na etapie potwierdzania. HTML nie jest obsługiwany';
$_['entry_kevin_title'] = 'Tytuł metody płatności';
$_['entry_log'] = 'dziennik kevin.:';
$_['entry_order_status'] = 'Status zamówienia';
$_['entry_order_statuses'] = 'Statusy zamówień';
$_['entry_partial_refund_action'] = 'Częściowo zwrócone';
$_['entry_partial_refund_status'] = 'Status zamówienia częściowo zwróconego';
$_['entry_payment_log'] = 'Dziennik płatności';
$_['entry_pending_status'] = 'Oczekujące';
$_['entry_position'] = 'Pozycja logo płatności';
$_['entry_redirect_preferred'] = 'Preferowane przekierowanie';
$_['entry_refunded_status'] = 'Status zamówienia ze zwrotem pieniędzy';
$_['entry_refund_actions'] = 'Działania związane ze zwrotem pieniędzy';
$_['entry_refund_log'] = 'Dziennik zwrotów';
$_['entry_refund_status'] = 'Status zwrotu';
$_['entry_sort_order'] = 'Kolejność sortowania:';
$_['entry_started_status'] = 'Rozpoczęto';
$_['entry_status'] = 'Status:';
$_['entry_total'] = 'Łącznie';

// Error
$_['error_bcmod'] = 'Nie można zweryfikować numeru konta, ponieważ moduł PHP &quot;bcmath&quot; nie jest zainstalowany na Twoim serwerze! Zainstaluj moduł &quot;bcmath&quot; lub poproś dostawcę serwera o jego zainstalowanie.';
$_['error_client_company'] = 'Wymagana nazwa firmy klienta!';
$_['error_client_c_symbol'] = 'Niedopuszczalne są znaki specjalne w nazwie klienta!';
$_['error_client_endpointSecret'] = 'Wymagany podpis klienta!';
$_['error_client_iban_empty'] = 'Wymagany nr konta klienta!';
$_['error_client_iban_valid'] = 'Nieprawidłowy nr konta klienta!';
$_['error_client_id'] = 'Wymagany identyfikator klienta!';
$_['error_client_secret'] = 'Wymagany tajny klucz klienta!';
$_['error_completed_status'] = 'Wymagany status zamówienia!';
$_['error_created_action'] = 'Działanie – zwrot pieniędzy wymagane!';
$_['error_failed_status'] = 'Wymagany status zamówienia!';
$_['error_partial_action'] = 'Działanie – zwrot pieniędzy wymagane!';
$_['error_partial_status'] = 'Wymagany status zamówienia!';
$_['error_payment_log_warning'] = 'Ostrzeżenie: Plik dziennika płatności %s to %s!';
$_['error_pending_status'] = 'Wymagany status zamówienia!';
$_['error_permission'] = 'Ostrzeżenie: Nie masz uprawnień do modyfikowania płatności kevin.';
$_['error_refunded_action'] = 'Działanie – zwrot pieniędzy wymagane!';
$_['error_refunded_status'] = 'Wymagany status zamówienia!';
$_['error_refund_log_warning'] = 'Ostrzeżenie: Plik dziennika zwrotu pieniędzy %s to %s!';
$_['error_started_status'] = 'Wymagany status zamówienia!';
$_['error_title'] = 'Wymagany tytuł płatności lub logo płatności!';
$_['error_warning'] = 'Sprawdź dokładnie ustawienia pod kątem błędów!';
$_['error_client'] = 'Nie można połączyć się z <span style="font-weight: 600; color:red;">kevin. </span> z powodu błędu serwera!';

// Help
$_['help_bank_name_enbl'] = 'Włącz nazwę banku na stronie realizacji transakcji.';
$_['help_bank_title'] = 'Zamiast tytułu metody płatności możesz dodać tylko logo banku.';
$_['help_client_endpointSecret'] = 'Twój tajny klucz punktu końcowego z pulpitu nawigacyjnego kevin.';
$_['help_client_id'] = 'Twój identyfikator klienta z pulpitu nawigacyjnego kevin.';
$_['help_client_secret'] = 'Twój tajny klucz klienta z pulpitu nawigacyjnego kevin.';
$_['help_height'] = 'Ustaw maksymalną wysokość logo płatności w pikselach dla metody płatności w kroku realizacji płatności. Szerokość obrazka zostanie zmieniona proporcjonalnie.';
$_['help_iban_format'] = 'Format numeru konta dla Litwy powinien składać się z dwóch liter i 18 cyfr. Przykład: LT599386327515536498.';
$_['help_log'] = 'Jeśli opcja kevin. log jest włączona, pliki kevin_payment.log i kevin_refund.log zostaną zapisane. Można je łatwo sprawdzić, pobrać lub wyczyścić.';
$_['help_position'] = 'Pozycja logo metody płatności obok nazwy metody płatności.';
$_['help_total'] = 'Minimalna kwota zamówienia';
$_['help_width'] = 'Ustaw maksymalną szerokość logo płatności w pikselach dla metody płatności w kroku realizacji płatności. Wysokość obrazka zostanie zmieniona proporcjonalnie.';
