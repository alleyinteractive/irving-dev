/* global wp */

import PropTypes from 'prop-types';

const {
  data: {
    withSelect,
  },
  date: {
    dateI18n,
  },
  i18n: {
    __,
  },
} = wp;

const ScheduleLabel = (props) => {
  const {
    meta: {
      alleypack_schedule_unpublish: date,
    },
  } = props;

  return date
    ? dateI18n('M j, Y g:i a', date)
    : __('Never', 'alleypack');
};

ScheduleLabel.propTypes = {
  meta: PropTypes.shape({}).isRequired,
};

export default withSelect((select) => ({
  meta: select('core/editor').getEditedPostAttribute('meta'),
}))(ScheduleLabel);
