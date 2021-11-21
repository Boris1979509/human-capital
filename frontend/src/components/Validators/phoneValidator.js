import {helpers} from 'vuelidate/lib/validators';

export default helpers.regex('phone', /^(\+\d) \d{3} \d{3}-\d{2}-\d{2}$/)