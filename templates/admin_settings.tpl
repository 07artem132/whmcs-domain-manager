<form role="form" id="settings" class="label-form">
	<fieldset>
		<br/>
		<input type="hidden" name="sdns_form" value="settings">
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_type">Разрешенные записи для пользователей:</label>
			</div>
			<div class="col-md-9">
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_a" id="sdns_type_a" type="checkbox" {if 'A'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_a">A</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_aaaa" id="sdns_type_aaaa" type="checkbox" {if 'AAAA'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_aaaa">AAAA</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_alias" id="sdns_type_alias" type="checkbox" {if 'ALIAS'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_alias">ALIAS</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_caa" id="sdns_type_caa" type="checkbox" {if 'CAA'|in_array:$records} CHECKED{/if}>
						<label for="sdns_type_caa">CAA</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_cname" id="sdns_type_cname" type="checkbox" {if 'CNAME'|in_array:$records} CHECKED{/if}>
						<label for="sdns_type_cname">CNAME</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_hinfo" id="sdns_type_hinfo" type="checkbox" {if 'HINFO'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_hinfo">HINFO</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_mx" id="sdns_type_mx" type="checkbox" {if 'MX'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_mx">MX</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_naptr" id="sdns_type_naptr" type="checkbox" {if 'NAPTR'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_naptr">NAPTR</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_ns" id="sdns_type_ns" type="checkbox" {if 'NS'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_ns">NS</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_ptr" id="sdns_type_ptr" type="checkbox" {if 'PTR'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_ptr">PTR</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_rp" id="sdns_type_rp" type="checkbox" {if 'RP'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_rp">RP</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_soa" id="sdns_type_soa" type="checkbox" disabled>
						<label for="sdns_type_soa">SOA</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_spf" id="sdns_type_spf" type="checkbox" {if 'SPF'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_spf">SPF</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_srv" id="sdns_type_srv" type="checkbox" {if 'SRV'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_srv">SRV</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_sshfp" id="sdns_type_sshfp" type="checkbox" {if 'SSHFP'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_sshfp">SSHFP</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_tlsa" id="sdns_type_tlsa" type="checkbox" {if 'TLSA'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_tlsa">TLSA</label>
					</div>
				</div>
				<div class="col-md-2">
					<div class="checkbox">
						<input name="sdns_type_txt" id="sdns_type_txt" type="checkbox" {if 'TXT'|in_array:$records}CHECKED{/if}>
						<label for="sdns_type_txt">TXT</label>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<h3>Настройки SOA по умолчанию</h3>
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_soa_hostmaster">Контактный адрес ответственного за администрирование файла зоны:</label>
			</div>
			<div class="col-md-3">
				<div id="sdns_soa_hostmaster_field">
					<input type="text" class="form-padding form-control" name="sdns_soa_hostmaster" id="sdns_soa_hostmaster" value="">
				</div>
			</div>
			<div class="col-md-6">
				<label class="info_text" for="sdns_soa_hostmaster">Обратите внимание на то, что вместо «@» используется «.»</label>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 text-right  title">
				<label for="sdns_soa_serial">Серийный номер файла зоны:</label>
			</div>
			<div class="col-md-3">
				<div id="sdns_soa_serial_field">
					<select class="form-padding form-control" name="sdns_soa_serial" id="sdns_soa_serial">
						<option  value="default">Default (yyyymmddcc)</option>
						<option   value="epoch">Epoch (Unix time)</option>
						<option   value="zero">Last edited record time</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<label class="info_text" for="sdns_soa_serial">Оставьте это по умолчанию, если вы не уверены, что это делаете!</label>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_soa_refresh">Refresh:</label>
			</div>
			<div class="col-md-3">
				<div id="sdns_soa_refresh_field">
					<input type="text" class="form-padding form-control" name="sdns_soa_refresh" id="sdns_soa_refresh" value="">
				</div>
			</div>
			<div class="col-md-6">
                <label class="info_text" for="sdns_soa_serial">Время (в секундах) ожидания вторичного DNS перед запросом SOA-записи с первичного. По истечении данного времени, вторичный DNS обращается к первичному, для получения копии текущей SOA-записи. Первичный DNS-сервер выполняет этот запрос. Вторичный DNS-сервер сравнивает полученный серийный номер зоны с имеющимся. Если они отличаются, то осуществляется запрос к первичному DNS-серверу на трансфер зоны.</label>
            </div>
		</div>
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_soa_retry">Retry:</label>
			</div>
			<div class="col-md-3">
				<div id="sdns_soa_retry_field">
					<input type="text" class="form-padding form-control" name="sdns_soa_retry" id="sdns_soa_retry" value="">
				</div>
			</div>
			<div class="col-md-6">
                <label class="info_text" for="sdns_soa_serial">
                    Время в секундах, вступает в действие тогда, когда первичный DNS-сервер недоступен. Интервал времени, по истечении которого вторичный DNS должен повторить попытку синхронизировать описание зоны с первичным.
                </label>
            </div>
		</div>
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_soa_expire">Expire:</label>
			</div>
			<div class="col-md-3">
				<div id="sdns_soa_expire_field">
					<input type="text" class="form-padding form-control" name="sdns_soa_expire" id="sdns_soa_expire" value="">
				</div>
			</div>
			<div class="col-md-6">
                <label class="info_text" for="sdns_soa_serial">
                    Время (в секундах), в течение которого вторичный DNS будет пытаться завершить синхронизацию зоны с первичным. Если это время истечет до того, как синхронизация осуществится, зона на вторичном DNS-сервере истечет, и он перестанет обслуживать запросы об этой зоне.
                </label>
            </div>
		</div>
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_soa_ttl">Минимальный TTL:</label>
			</div>
			<div class="col-md-3">
				<div id="sdns_soa_ttl_field">
					<input type="text" class="form-padding form-control" name="sdns_soa_ttl" id="sdns_soa_ttl" value="">
				</div>
			</div>
			<div class="col-md-6">
                <label class="info_text" for="sdns_soa_serial">
                    Минимальное время жизни, применяемое ко всем ресурсным записям зоны. Это значение применяется в ответах на запросы с целью проинформировать остальные серверы, сколько времени они могут хранить данные в кэше.
                </label>
            </div>
		</div>
		<hr />
		<h3>Лимиты</h3>
		<div class="row">
			<div class="col-md-3 text-right title">
				<label for="sdns_record_limit">Лимит записей:</label>
			</div>
			<div class="col-md-1">
				<div id="sdns_record_limit_field">
					<input name="sdns_record_limit" type="text" class="form-control" name="sdns_record_limit" id="sdns_record_limit" value="">
				</div>
			</div>
			<div class="col-md-8">
				<label class="info_text" for="sdns_record_limit">Ограничить максимально допустимые записи в зоне (0 = неограниченно).</label>
			</div>
		</div>
		<hr />
		<div class="row text-center"> <br />
			<input class="btn btn-primary" type="button" onclick="window.updateSettings('settings');" value="Сохранить изменения" />
		</div>
	</fieldset>
</form>
