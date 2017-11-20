<script>
    window.eaData = {};
    var ea = window.eaData;
    ea.Locations = <?php echo $this->models->get_pre_cache_json('ea_locations', array('name' => 'ASC')); ?>;
    ea.Services = <?php echo $this->models->get_pre_cache_json('ea_services', array('name' => 'ASC')); ?>;
    ea.Workers = <?php echo $this->models->get_pre_cache_json('ea_staff', array('name' => 'ASC')); ?>;
    ea.MetaFields = <?php echo $this->models->get_pre_cache_json('ea_meta_fields', array('position' => 'ASC')); ?>;
    ea.Status = <?php echo json_encode($this->logic->getStatus()); ?>
</script>