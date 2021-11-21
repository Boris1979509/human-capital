export default function(val) {
  return new Date(val).toLocaleDateString('ru-RU', {
    day: 'numeric',
    month: 'long',
  });
}
