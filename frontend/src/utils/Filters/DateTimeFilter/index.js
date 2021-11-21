export default function(val) {
  if (!val) {
    return '-';
  }
  return new Date(val).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
    hour: 'numeric',
    minute: 'numeric',
  });
}
