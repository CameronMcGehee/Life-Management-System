const { options } = require("../../routes/admin");

const moment = require('moment-timezone');

module.exports = {

	customerTable: function(options){

		// Init Settings ------------------------------------------------------

		var output = '';

		if (undefined != typeof options['renderId']) {
			options['renderId'] = rand(0, 9999);
		}
		if (undefined != typeof options['rootPathPrefix']) {
			options['rootPathPrefix'] = './';
		}
		if (undefined != typeof options['queryParams']) {
			options['queryParams'] = '';
		}
		if (undefined != typeof options['businessId']) {
			options['businessId'] = $_SESSION['ultiscape_businessId'];
		} else {
			throw new Exception("No businessId set to pull customers from (in customerTable)");
		}
		if (undefined != typeof options['maxRows'] || typeof options['maxRows'] != int) {
			options['maxRows'] = 10;
		}
		if (undefined != typeof options['pageGetVarName']) {
			options['pageGetVarName'] = '-p';
		}
		if (undefined != typeof options['sortGetVarName']) {
			options['sortGetVarName'] = '-s';
		}
		if (undefined != typeof options['searchGetVarName']) {
			options['searchGetVarName'] = '-q';
		}
		if (undefined != typeof options['usePage'] || typeof options['usePage'] != int) {
			options['usePage'] = 1;
		}
		if (undefined != typeof options['showAdd']) {
			options['showAdd'] = false;
		}
		if (undefined != typeof options['showSort']) {
			options['showSort'] = false;
		}
		if (undefined != typeof options['showSearch']) {
			options['showSearch'] = true;
		}
		if (undefined != typeof options['useSearch']) {
			options['useSearch'] = '';
		}
		if (undefined != typeof options['useSort'] || 
			(
				!options['useSort'].includes('az') &&
				!options['useSort'].includes('za') &&
				!options['useSort'].includes('newest') &&
				!options['useSort'].includes('oldest')
			)
		) {
			options['useSort'] = 'az';
		}
		if (undefined != typeof options['showPageNav']) {
			options['showPageNav'] = true;
		}
		if (undefined != typeof options['showEmails']) {
			options['showEmails'] = true;
		}
		if (undefined != typeof options['showPhoneNumbers']) {
			options['showPhoneNumbers'] = true;
		}
		if (undefined != typeof options['showBillingAddress']) {
			options['showBillingAddress'] = false;
		}
		if (undefined != typeof options['showDateAdded']) {
			options['showDateAdded'] = false;
		}
		if (undefined != typeof options['showBatch']) {
			options['showBatch'] = false;
		}

		var currentBusiness = options['businessId'];

		var renderId = renderId;

		moment().tz("America/New_York").format('Y-m-d H:i:s');

		// GENERATE TABLE ------------------------------------------------------













		return output;
	}

}
