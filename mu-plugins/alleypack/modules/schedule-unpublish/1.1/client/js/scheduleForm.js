/* global React, wp */

import PropTypes from 'prop-types';

const {
  components: {
    Button,
    DateTimePicker,
  },
  compose: {
    compose,
  },
  data: {
    withDispatch,
    withSelect,
  },
  element: {
    Fragment,
  },
  i18n: {
    __,
  },
} = wp;

const ScheduleForm = (props) => {
  const {
    meta: {
      alleypack_schedule_unpublish: unpubDate,
    },
    defaultDate,
    setMeta,
  } = props;

  const currentDate = unpubDate || defaultDate;

  return (
    <Fragment>
      {unpubDate && (
        <Button
          type="button"
          className="schedule-unpublish__cancel"
          onClick={() => setMeta(
            'alleypack_schedule_unpublish',
            ''
          )}
          isLink
          isDestructive
        >
          {__('Remove unpublish date', 'alleypack')}
        </Button>
      )}
      <DateTimePicker
        key="date-time-picker"
        currentDate={currentDate}
        onChange={(value) => setMeta(
          'alleypack_schedule_unpublish',
          value
        )}
        is12Hour
      />
    </Fragment>
  );
};

ScheduleForm.propTypes = {
  meta: PropTypes.shape({
    alleypack_schedule_unpublish: PropTypes.string.isRequired,
  }).isRequired,
  defaultDate: PropTypes.string.isRequired,
  setMeta: PropTypes.func.isRequired,
};

export default compose([
  withSelect((select) => ({
    meta: select('core/editor').getEditedPostAttribute('meta'),
    defaultDate: select('core/editor').getEditedPostAttribute('date'),
  })),
  withDispatch((dispatch) => ({
    setMeta: (key, value) => {
      const meta = {};
      meta[key] = value;
      dispatch('core/editor').editPost({ meta });
    },
  })),
])(ScheduleForm);
