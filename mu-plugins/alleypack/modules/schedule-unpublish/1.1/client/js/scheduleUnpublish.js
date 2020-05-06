/* global React, wp */

import ScheduleLabel from './scheduleLabel';
import ScheduleForm from './scheduleForm';
import '../scss/style.scss';

const {
  components: {
    Button,
    Dropdown,
  },
  editPost: {
    PluginPostStatusInfo,
  },
  i18n: {
    __,
  },
} = wp;

const ScheduleUnpublish = () => (
  <PluginPostStatusInfo>
    <label // eslint-disable-line jsx-a11y/label-has-for
      htmlFor="schedule-unpublish"
    >
      {__('Unpublish', 'alleypack')}
    </label>
    <Dropdown
      position="bottom left"
      contentClassName="schedule-unpublish__dialog"
      renderToggle={({ onToggle, isOpen }) => (
        <Button
          type="button"
          className="schedule-unpublish__toggle"
          onClick={onToggle}
          aria-expanded={isOpen}
          aria-live="polite"
          isLink
        >
          <ScheduleLabel />
        </Button>
      )}
      renderContent={() => (
        <ScheduleForm
          id="schedule-unpublish"
        />
      )}
    />
  </PluginPostStatusInfo>
);

export default ScheduleUnpublish;
