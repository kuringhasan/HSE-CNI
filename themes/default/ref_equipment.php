<link rel="stylesheet" href="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

<style>
.btn-action{
	text-align:center;
}
.col-number{
	text-align:center;
}

#dome-location-list tr th{
	vertical-align:middle;
	text-align:center;
}
</style>

<div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header"></div>
        <div class="box-body">
          <table id="equipment-list" class="table table-bordered table-hover dataTable"  style="width:100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Kategori</th>
								<th>Nama</th>
                <th>Nomor</th>
                <th>Tipe</th>
                <th>Kontraktor</th>
                <th>aksi</th>
								<th></th>
              </tr>
            </thead>
            <thead>
              <tr>
                  <th></th>
                  <th></th>
                  <th></th>
									<th></th>
                  <th></th>
                  <th></th>
                  <th style="text-align:center">
                    <button class="btn btn-primary btn-xs" onclick="createForm()"><i class="fa fa-fw fa-plus-circle"></i></button>
                  </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="reusable-modal" tabindex="-1" role="dialog" aria-labelledby="universal-modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Reusable Modal</h4>
        </div>
        <div class="modal-body"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $theme_path;?>adminlte-2.4.4/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $theme_path;?>datatables/lang.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.20/lodash.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>
<script>
        $(function() {
            $.LoadingOverlaySetup({
                image: "<?php echo $theme_path;?>images/loading.svg",                
            });
        });
    </script>
<script src="<?php echo $theme_path;?>pages/form-helper.js?dev"></script>
<script type="text/javascript">
const equipmentTable = $('#equipment-list');
const reusableModal = $('#reusable-modal');
const currentUrl = window.location.href;
const contractors = [];

$(async function() {
	const categories = JSON.parse(await $.get(currentUrl + '/getCategories').done(categories => categories));

	equipmentTable.DataTable({
		language: dtLang,
		ajax: {
			url: currentUrl + '/data',
			dataSrc: function (json) {
				const data = json.data;
				const contractorData = json.contractors;

				for ( var i=0, ien = data.length ; i < ien ; i++) {
					data[i]['rowNumber'] = data.length - i;
				}

				for ( var i=0, ien = contractorData.length ; i < ien ; i++) {
					contractors.push(contractorData[i]);
				}

				return data;
			}
		},
		order: [
			[7, 'ASC']
		],
		columns: [
			{ data: 'rowNumber', sWidth: '5%' },
			{ data: 'category', render: function (data, type, row, meta) { const {category} = _.find(categories, (c) => c.code == data); return category; } },
			{ data: 'name' },
			{ data: 'nomor' },
			{ data: 'type' },
			{ data: 'contractor', render: function (data, type, row, meta) { const contractor = _.find(contractors, (c) => c.id == row.partner_id); return (contractor) ? contractor.name : '-'; } },
			{ data: 'id', sortable: false, render: function (data, type, row, meta) { return tableActionButton(data); }, className: 'text-center' },
			{ data: 'id', visible: false },
		]
	});
})

const tableActionButton = (id) => {
	const buttonUpdate = $('<button/>', {
			html: '<i class="fa fa-gear"></i>',
			class: 'btn btn-primary btn-xs',
			onclick: 'updateForm('+id+')'
	});

	const buttonDelete = $('<button/>', {
			html: '<i class="fa fa-trash"></i>',
			class: 'btn btn-primary btn-xs',
			onclick: 'deleteConfirm('+id+')'
	});

	return buttonUpdate.prop('outerHTML') + '&nbsp' + buttonDelete.prop('outerHTML');
}

const createForm = async () => {
	const form = await $.get(currentUrl + '/createForm').done(html => html);

	const buttonSave = $('<button/>', {
			html: '<i class="fa fa-save"></i> Simpan',
			class: 'btn btn-primary btn-xs',
			click: createSubmit
	});

	const buttonCancel = $('<button/>', {
			html: '<i class="fa fa-times"></i> Batal',
			class: 'btn btn-primary btn-xs',
			click: modalHide
	});

	modalShow('Input Equipment', form, $.merge(buttonCancel, buttonSave));
}

const createSubmit = () => {
		const modal = $(reusableModal);

		if (validateForm(modal.find('form'))) {
				const data = getFormData(modal.find('form'));
				modal.find('.modal-content').LoadingOverlay('show');
				$.post(currentUrl + '/checkNumber', {
					number: data.nomor
				}).done((response) => {
					const { exists } = JSON.parse(response);
					modal.find('.modal-content').LoadingOverlay('hide', true);

					if (exists < 1) {
						$.post(currentUrl + '/create', data).done(() => { equipmentTable.DataTable().ajax.reload(); });
						modalHide();
						$('#error-alert').hide();
					} else {
						$('#error-alert').find('.alert-content').html('Nomor equipment telah digunakan');
						$('#error-alert').show();
					}
				});
		}
}

const updateForm = async (id) => {
	const form = await $.get(currentUrl + '/updateForm', {id}).done(html => html);

	const buttonSave = $('<button/>', {
			html: '<i class="fa fa-save"></i> Simpan',
			class: 'btn btn-primary btn-xs',
			click: updateSubmit
	});

	const buttonCancel = $('<button/>', {
			html: '<i class="fa fa-times"></i> Batal',
			class: 'btn btn-primary btn-xs',
			click: modalHide
	});

	modalShow('Update Equipment', form, $.merge(buttonCancel, buttonSave));
}

const updateSubmit = () => {
	const modal = $(reusableModal);

	if (validateForm(modal.find('form'))) {
			const data = getFormData(modal.find('form'));
			$.post(currentUrl + '/update', data).done(() => { equipmentTable.DataTable().ajax.reload(); });
			modalHide();
	}
}

const deleteConfirm = (id) => {
	const data = equipmentTable.DataTable().rows().data();
	const equipment = _.find(data, (row) => row.id == id);
	if (equipment) {
		const buttonConfirm = $('<button/>', {
				html: '<i class="fa fa-trash"></i> Hapus',
				class: 'btn btn-primary btn-xs',
				onclick: `deleteSubmit(${equipment.id})`
		});

		const buttonCancel = $('<button/>', {
				html: '<i class="fa fa-times"></i> Batal',
				class: 'btn btn-primary btn-xs',
				click: modalHide
		});

		const textConfirm = $('<h4/>', {
				html: 'Yakin equipment "<b>' + equipment.name + '</b>" akan dihapus?',
				class: 'modal-title'
		});

		modalShow('Konfirmasi Hapus Data', textConfirm, $.merge(buttonCancel, buttonConfirm));
	}
}

const deleteSubmit = (id) => {
	$.post(currentUrl + '/delete', {id}).done(() => { equipmentTable.DataTable().ajax.reload(null, false); });
	modalHide();
}

const modalShow = (title, content = '', footer = '') => {
		const modal = $(reusableModal);
		modal.find('.modal-title').text(title);
		modal.find('.modal-body').html(content);
		modal.find('.modal-footer').html(footer);
		modal.modal('show');
}

const modalHide = () => {
	$(reusableModal).modal('hide');
}
</script>
