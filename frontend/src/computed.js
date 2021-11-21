export default {
  $user() {
    return this.$store.getters.GET_USER;
  },
  $organization() {
    return this.$store.getters.GET_ORG_DATA;
  },
  $employer() {
    return this.$store.getters.GET_EMPLOYER_DATA;
  },
  $loginStatus() {
    return this.$store.getters.GET_LOGIN_STATUS;
  },
  $personal() {
    return this.$store.getters.GET_PERSONAL_DATA;
  },
  $cities() {
    return this.$store.getters.GET_CITIES_LIST;
  },
  $universities() {
    return this.$store.getters.GET_UNIVERSITIES_LIST;
  },
  $dictionaries() {
    return this.$store.getters.GET_DICTIONARIES_LIST;
  },
  $professions() {
    return this.$store.getters.GET_PROFESSIONS_LIST;
  },
  $content() {
    return this.$store.getters.GET_CONTENT_DATA;
  },
  $programs() {
    return this.$store.getters.GET_PROGRAMS_LIST;
  },
  $program() {
    return this.$store.getters.GET_PROGRAM;
  },
  $regionName() {
    return this.$store.getters.GET_REGION_NAME;
  },
  $regionCounters() {
    return this.$store.getters.GET_REGION_DATA;
  },
  $journalPart() {
    return this.$store.getters.GET_JOURNAL_PART;
  },
  $instTypes() {
    return this.$store.getters.GET_INST_TYPES;
  },
  $selections() {
    return this.$store.getters.GET_SELECTIONS_LIST;
  },
  $events() {
    return this.$store.getters.GET_EVENTS_LIST;
  },
  $filteredPrograms() {
    return this.$store.getters.GET_FILTERED_PROGRAMS_LIST;
  },
  $filteredProgramsMeta() {
    return this.$store.getters.GET_FILTERED_PROGRAMS_LIST_META;
  },
  $maxProgramCost() {
    return this.$store.getters.GET_MAX_PROGRAM_COST;
  },
  $getOrgAvatar() {
    return this.$store.getters.GET_ORG_AVATAR;
  },
  $getUserAvatar() {
    return this.$store.getters.GET_USER_AVATAR;
  },
  $getEmployerAvatar() {
    return this.$store.getters.GET_EMPLOYER_AVATAR;
  },
};
