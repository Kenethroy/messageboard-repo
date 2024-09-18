<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($contacts)): ?>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo h($contact['Contact']['name']); ?></td>
                <td><?php echo h($contact['Contact']['email']); ?></td>
                <td><?php echo h($contact['Contact']['phone']); ?></td>
                <td>
                    <?php echo $this->Html->link('View', array('action' => 'view', $contact['Contact']['id'])); ?>
                    <?php echo $this->Html->link('Edit', array('action' => 'edit', $contact['Contact']['id'])); ?>
                    <?php
                    echo $this->Form->postLink('Delete', array('action' => 'delete', $contact['Contact']['id']),
                        array('confirm' => 'Are you sure?'));
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No contacts found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
